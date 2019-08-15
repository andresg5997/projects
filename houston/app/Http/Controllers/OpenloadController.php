<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use File;
use App\Media;
use IMG;
use DB;
// use Event;

class OpenloadController extends Controller
{
    //
    public function index()
    {
        return view('openload-cloning');
    }

    public function cloneFiles(Request $request)
    {
        $path = $request->file('file')->store('public');

        $urls = file(storage_path('app/'.$path));

        $domain = url('/').'/';

        $upload_session = session()->get('upload_session');

        if (! $upload_session) {
            $upload_session = '99999999_99999999';
        }

        $local_path = 'uploads/content/media/';

        foreach ($urls as $url) {
            $url = trim($url);
            if (filter_var($url, FILTER_VALIDATE_URL)) {
                // check if it is Openload URL
                if (str_contains($url, 'openload')) {
                    $this->openloadClone($request, $url, $upload_session, 0, $domain, $local_path);
                }
            }
        }

        return back();
    }

    public function openloadClone($request, $url, $upload_session, $private, $domain, $local_path) {
        $user_agent = $request->header('User-Agent');

        $cmd = 'youtube-dl -e -g  "' . $url . '" --user-agent "' . $user_agent . '"';
        $out = shell_exec($cmd);
        $info_arr = explode(PHP_EOL, $out);
        // make sure the array is set, because it seems that sometimes
        if(isset($info_arr[1])) {
            $name = $info_arr[0];
            $stream_url = $info_arr[1];

            $ch = curl_init($stream_url);
            curl_setopt($ch, CURLOPT_NOBODY, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);

            curl_exec($ch);

            $file_size = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
            $mime_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);

            curl_close($ch);

            if (str_contains($mime_type, 'video')) {
                $extension = pathinfo($name, PATHINFO_EXTENSION);
                // without the extension
                $original_name = pathinfo($name, PATHINFO_FILENAME);

                $original_name = str_replace('_', ' ', $original_name);

                // since each video file gets converted by OL to mp4, the name will include the original extension
                $original_name = pathinfo($original_name, PATHINFO_FILENAME);

                $new_file_name = make_slug($original_name).'-'.rand(000, 999);

                $ffmpeg_path = 'ffmpeg';

                // Get the duration
                $parameters = "2>&1 | grep Duration | cut -d ' ' -f 4 | sed s/,//";
                $cmd_duration_ffmpeg = "$ffmpeg_path -i $stream_url $parameters";
                $duration = shell_exec($cmd_duration_ffmpeg);

                $upload_session_path = public_path('uploads/content/media/'.$upload_session.'/');
                if (!File::exists($upload_session_path)) {
                    File::makeDirectory($upload_session_path);
                }

                $path = getcwd();
                $save_video_thumbnail = $path.'/'.$local_path.$upload_session.'/'.$new_file_name.'-thumbnail.jpg';
                $thumbnail_path = $upload_session.'/'.$new_file_name.'-thumbnail.jpg';

                $thumbnail_url = $this->createVideoThumbnails($duration, $ffmpeg_path, $stream_url, $save_video_thumbnail, $local_path, $thumbnail_path, $upload_session, $new_file_name, $domain);

                $handler_output = array('type' => 'video', 'duration' => $duration, 'thumbnail_url' => $thumbnail_url);

                // make appropriate media database entries
                $this->createMedia($new_file_name, $request, $upload_session, $private, $handler_output, $original_name, $extension, $file_size);
                $media = Media::where('slug', $new_file_name)->first();
                $media->update(['cloned' => $url]);
                $key = $media->key;
                $my_file = fopen("sql-script.txt", "a+") or die("Unable to open file!");
                $txt = 'UPDATE `links` SET `url` = replace(url, "'.$url.'", "https://clooud.tv/embed/'.$key.'");'."\n";
                //UPDATE `links` SET `url` = replace(url, 'https://OPENLOAD_LINK_IVE_SEND_TO_U', 'https://CLOOUD_LINK_YOU_CRATE_FOR_ME');
                fwrite($my_file, $txt);
            }
        }
    }

    public function createVideoThumbnails($duration, $ffmpeg_path, $full_video_path, $save_video_thumbnail, $local_path, $thumbnail_path, $upload_session, $new_file_name, $domain)
    {
        sscanf($duration, '%d:%d:%d.%d', $hours, $minutes, $seconds, $milliseconds);

        if ($hours > 0) {
            $frame_time = '00:30:00';
        } elseif ($minutes > 1) {
            if (strlen($minutes) == 1) {
                $minutes = floor(($minutes / 2));
                $frame_time = "00:0$minutes:00";
            } else {
                $minutes = floor(($minutes / 2));
                if (strlen($minutes) == 1) {
                    $frame_time = "00:0$minutes:00";
                } else {
                    $frame_time = "00:$minutes:00";
                }
            }
        } elseif ($minutes == 1) {
            $frame_time = '00:00:30';
        } else {
            // minutes is 0
            if ($seconds > 1) {
                $frame_time = floor(($seconds / 2));
            } elseif ($seconds == 1) {
                $frame_time = '01:00';
            } else {
                $frame_time = "00.$milliseconds";
            }
        }

        $cmd_frame_ffmpeg = "$ffmpeg_path -ss $frame_time  -i $full_video_path -vframes:v 1 $save_video_thumbnail";
        exec($cmd_frame_ffmpeg, $out, $thumbnail_status);

        // create a small thumbnail for /uploads File Uploader Preview
        if (0 === $thumbnail_status) {
            // create a thumbnail for the JSON return
            $width = 80;
            $height = 80;

            $video_thumbnail_sm = IMG::make($local_path.$thumbnail_path);
            $video_thumbnail_sm->height() > $video_thumbnail_sm->width() ? $width = null : $height = null;
            $video_thumbnail_sm->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
            });

            $video_thumbnail_sm->save($local_path.$upload_session.'/'.$new_file_name.'-thumbnail-sm.jpg');
            $thumbnail_url = $domain.$local_path.$upload_session.'/'.$new_file_name.'-thumbnail-sm.jpg';

            $width = 800;
            $height = 600;

            $video_thumbnail_l = IMG::make($local_path.$thumbnail_path);
            $video_thumbnail_l->height() > $video_thumbnail_l->width() ? $width = null : $height = null;
            $video_thumbnail_l->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
            });
            $video_thumbnail_l->save($local_path.$upload_session.'/'.$new_file_name.'-thumbnail-l.jpg', 60);
        } else {
            // if for some reason a thumbnail could not get created
            $thumbnail_url = $domain.'assets/images/video_file.png';
        }

        return $thumbnail_url;
    }

    public function createMedia($new_file_name, $request, $upload_session, $private, $handler_output, $original_name, $extension, $file_size)
    {
        $request->merge([
            'slug'           => $new_file_name,
            'upload_session' => $upload_session,
            'private'        => $private,
            'type'           => $handler_output['type'],
            'title'          => $original_name,
            'category_id'    => 5, // 5 is default / other category
            'ip'             => request()->ip(),
            'key'            => str_random(6),
            'user_id'        => 114,
        ]);

        // Start transaction
        DB::beginTransaction();

        //$media = $request->user()->media()->create($request->all());
        $media = Media::create($request->all());

        // Handle Point Event
        // Event::fire('add.points.upload_media', 114);

        // $this->sendEmailAboutNewMediaToFollowers($request, $handler_output, $new_file_name, $original_name);

        // create media attachment
        $this->createMediaAttachment($handler_output, $original_name, $new_file_name, $extension, $file_size, $upload_session, $media);

        // $this->sendEmailAboutUploadToAdmin($request, $original_name, $new_file_name);

        DB::commit();
    }

    public function createMediaAttachment($handler_output, $original_name, $new_file_name, $extension, $file_size, $upload_session, $media)
    {
        $attachment_data = [
            'file_original_name'    => $original_name,
            'file_name'             => $new_file_name,
            'file_mime'             => $extension,
            'file_size'             => $file_size,
            'upload_session'        => $upload_session,
            'duration'              => $handler_output['duration'],
        ];

        $attachment_data = json_encode($attachment_data);

        return $media->attachments()->create([
            'content'        => $attachment_data,
            'upload_session' => $upload_session,
        ]);
    }

    /**
     *
     * Split large files into smaller ones
     * @param string $source Source file
     * @param string $targetpath Target directory for saving files
     * @param int $lines Number of lines to split
     * @return void
     */
    public function split_file($source, $targetpath, $lines = 10)
    {
        $i=0;
        $j=1;
        $date = date("m-d-y");
        $buffer='';

        $handle = @fopen ($source, "r");
        while (!feof ($handle)) {
            $buffer .= @fgets($handle, 4096);
            $i++;
            if ($i >= $lines) {
                $fname = $targetpath."$j.txt";
                $this->saveToFile($buffer, $fname);
                $j++;
                $i=0;
            }
        }
        $fname = $targetpath."$j.txt";
        $this->saveToFile($buffer, $fname);
        fclose ($handle);
    }

    public function saveToFile(&$buffer, $fname)
    {
        if (!$fhandle = @fopen($fname, 'w')) {
            echo "Cannot open file ($fname)";
            exit;
        }
        if (!@fwrite($fhandle, $buffer)) {
            echo "Cannot write to file ($fname)";
            exit;
        }
        fclose($fhandle);
        $buffer = '';
    }

    public function shrink()
    {
        $this->split_file('links.txt', storage_path('app/chunks/links'), 6000);
        return 'finished';
    }
}
