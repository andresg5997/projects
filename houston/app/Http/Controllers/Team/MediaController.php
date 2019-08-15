<?php

namespace App\Http\Controllers\Team;

use App\Attachment;
use App\Category;
use App\Comment;
use App\Followers;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateMediaRequest;
use App\Http\Requests\User\UploadMediaRequest;
use App\Jobs\ConvertVideoToStreamableFormat;
use App\Jobs\SendEmail;
use App\Jobs\UploadToExternalDisk;
use App\Media;
use App\Team;
use App\User;
use Auth;
use Carbon\Carbon;
use Conner\Tagging\Model\Tag;
use DB;
use Event;
use File;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use IMG;
use Conner\Likeable\Like;
use Conner\Likeable\LikeCounter;
use Mail;
use Pion\Laravel\ChunkUpload\Exceptions\UploadMissingFileException;
use Pion\Laravel\ChunkUpload\Handler\ContentRangeUploadHandler;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Purifier;
use Storage;

class MediaController extends Controller
{
    public function __construct()
    {
        $comments_per_minutes = config('comments_per_minutes');
        $comments_throttle = ['throttle:'.$comments_per_minutes.',1'];
        $this->middleware($comments_throttle)->only(['putComment']);

        $this->middleware(function ($request, $next) {
            if (Auth::check()) {
                $uploads_per_day = config('uploads_per_day');
                $uploads_throttle = ['throttle:'.$uploads_per_day.',1440'];
                $this->middleware($uploads_throttle)->only(['uploadHandler']);
            } else {
                $uploads_per_day_per_guest = config('uploads_per_day_per_guest');
                $uploads_per_guest_throttle = ['throttle:'.$uploads_per_day_per_guest.',1440'];
                $this->middleware($uploads_per_guest_throttle)->only(['uploadHandler']);
            }

            return $next($request);
        });
    }

    public function index()
    {
        session()->put('upload_session', time().'_'.rand(00000000, 99999999));
        $categories = Category::all(['name', 'id']);
        $teams = Team::where('user_id', \Auth::id())->get();

        return view('teams.upload', compact('categories', 'teams'));
    }

    public function uploadHandler(UploadMediaRequest $request)
    {
        if ($request->input('direct-upload')) {
            return $this->directUploadHandler($request);
        } elseif ($request->input('remote_upload')) {
            return $this->remoteUploadHandler($request);
        } else {
            throw new UploadMissingFileException();
        }
    }

    public function directUploadHandler(Request $request)
    {
        // dd('$hi');
        // dd($request->except(''));
        $domain = url('/').'/';

        $response = ['files' => []];
        $upload_session = session()->get('upload_session');
        $local_path = 'uploads/content/media/';

        $files = [];
        // Get array of files from request
        $files = $request->file('files');

        if (!is_array($files)) {
            throw new UploadMissingFileException();
        }
        // dd($files);
        $medias = [];
        foreach ($files as $file) {
            // create the file receiver
            $receiver = new FileReceiver($file, $request, ContentRangeUploadHandler::class);

            // check if the upload is success
            if ($receiver->isUploaded()) {
                // receive the file
                $save = $receiver->receive();

                // check if the upload has finished or if chunk files are being sent
                if ($save->isFinished()) {
                    $original_name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $new_file_name = make_slug($original_name).'-'.rand(000, 999);
                    $mime_type = $file->getClientMimeType();
                    $file_size = $file->getClientSize();
                    $extension = strtolower($file->getClientOriginalExtension());

                    // if private is checked, then media cannot be found on website and only be shared by link
                    $private = $this->isMediaPrivate($request, $original_name, $extension);

                    // check if it was a chunk upload
                    $isChunkedUpload = $save->handler()->isChunkedUpload();
                    if ($isChunkedUpload) {
                        $chunk_file_name = $save->handler()->getChunkFileName();
                        $file_size = $save->handler()->getBytesTotal();

                        // because uploaded file is handled through chunked file upload
                        $this->moveChunkedFileToPublic($local_path, $upload_session, $chunk_file_name, $new_file_name, $extension);
                    } else {
                        $uploaded_file = File::get($file);
                        $upload_type = 'direct';
                        // store the uploaded file which is not a chunked upload
                        $this->store($upload_session, $new_file_name, $extension, $uploaded_file, $upload_type);
                    }

                    // return thumbnail_url, type and duration and call videoHandler, imageHandler etc, to convert as well
                    $handler_output = $this->mediaHandler($mime_type, $local_path, $upload_session, $new_file_name, $extension, $domain);

                    // dd("First");
                    //

                    // make appropriate media database entries and final JSON response
                    $media = $this->createMedia($new_file_name, $request, $upload_session, $private, $domain, $handler_output[0], $response, $original_name, $extension, $file_size);
                    $medias[] = $media;
                } else {
                    // we are in chunk mode, lets send the current progress
                    /** @var ContentRangeUploadHandler $handler */
                    $handler = $save->handler();

                    // Add the completed file
                    $files[] = [
                        'start'    => $handler->getBytesStart(),
                        'end'      => $handler->getBytesEnd(),
                        'total'    => $handler->getBytesTotal(),
                        'finished' => false,
                    ];

                    return response()->json([
                        'done' => $handler->getPercentageDone(),
                    ]);
                }
            } else {
                throw new UploadMissingFileException();
            }
        }

        return $medias;
    }

    /*
     * Download files from inserted links
     * and save them
     */
    public function remoteUploadHandler(UploadMediaRequest $request)
    {
        $domain = url('/');
        $upload_session = session()->get('upload_session');
        $local_path = 'uploads/content/media/';

        $type = $request->input('type');

        // classic remote upload
        if ($type == "classic-remote") {
            $this->classicRemoteUpload($request, $upload_session, $local_path, $domain);
        } elseif ($type == "media-hosts") {
            if (command_exist('youtube-dl --help') && isEnabled('exec')) {
                $this->mediaHostsRemoteUpload($request, $upload_session, $local_path, $domain);
            }
        } elseif ($type == "gdrive") {
            $this->gdriveRemoteUpload($request, $upload_session, $local_path, $domain);
        } elseif ($type == "dropbox") {
            $this->dropboxRemoteUpload($request, $upload_session, $local_path, $domain);
        } elseif ($type == "clone") {
            $this->cloneRemoteUpload($request);
        }

        return response()->json('ok', 200);
    }

    public function edit(Request $request, $slug)
    {
        $categories = Category::all(['name', 'id'])->sortByDesc('id');
        $media = Media::where('slug', $slug)->firstOrFail();
        $attachment = Attachment::where('media_id', $media->id)->firstOrFail();
        $user_id = $media->user_id;
        $user = User::where('id', $user_id)->firstOrFail();
        $username = $user->username;
        $private = $media->private;
        $anonymous = $media->anonymous;
        $owner = ($request->user()->username == $username) ? true : false;
        $admin = ($request->user()->type == 'admin') ? true : false;

        $tags = $media->tags->pluck('name')->toArray();
        $tags = implode(',', $tags);

        $common_tags = Tag::orderBy('count', 'desc')->take(10)->get();

        if ($owner || $admin) {
            return view('media.edit', compact('categories', 'media', 'attachment', 'slug', 'private', 'tags', 'username', 'common_tags', 'password', 'anonymous'));
        } else {
            return abort(403);
        }
    }

    public function update(UpdateMediaRequest $request, $slug)
    {
        $request->all();
        $media = Media::where('slug', $slug)->firstOrFail();

        $user_id = $media->user_id;
        $user = User::where('id', $user_id)->firstOrFail();

        // attach tags and limit the amount
        if ($request->input('tags') != '') {
            $tags = explode(',', $request->input('tags'));
            if (count($tags) > config('max_tags_per_media')) {
                $tags = array_splice($tags, 2);
                $media->retag($tags);
            } else {
                $media->retag($tags);
            }
        } else {
            $media->retag([]);
        }

        $username = $user->username;
        $adblock_ask = $user->adblock_ask;
        $adblock_off = $user->adblock_off;
        $owner = ($request->user()->username == $username) ? true : false;
        $admin = ($request->user()->type == 'admin') ? true : false;
        $private = ($request->input('private') !== null) ? 1 : 0;
        $anonymous = ($request->input('anonymous') !== null) ? 1 : 0;
        $title = (!$request->input('title')) ? $media->title : $request->input('title');
        $description = (!$request->input('description')) ? $media->body : Purifier::clean($request->input('description'));
        $password = (!$request->input('media-password')) ? '' : bcrypt($request->input('media-password'));

        if ($owner || $admin) {
            $media->update([
                'title'       => $title,
                'body'        => $description,
                'category_id' => $request->input('category'),
                'private'     => $private,
                'anonymous'   => $anonymous,
                'password'    => $password,
            ]);

            if ($request->file('thumbnail')) {
                $this->updateThumbnail($media, $request);
            }

            flash('Media information has been updated!', 'success');

            return view('media.index', compact('media', 'owner', 'adblock_off', 'adblock_ask', 'password', 'anonymous', 'admin', 'user'));
        } else {
            return abort(403);
        }
    }

    public function destroy(Request $request, $slug)
    {
        $request->all();
        $media = Media::where('slug', $slug)->firstOrFail();
        $user_id = $media->user_id;
        $media_id = $media->id;
        $upload_session = $media->upload_session;

        $attachment = Attachment::where('media_id', $media_id)->firstOrFail();

        // Delete Tags, Comments, Flags, Tags
        $user = User::where('id', $user_id)->firstOrFail();
        $type = User::where('id', Auth::id())->first()->type;
        $username = $user->username;
        $owner = ($request->user()->username == $username) ? true : false;

        if ($owner || $type == 'admin') {
            Like::where('likable_type', 'App\Media')->where('likable_id', $media->id)->delete();
            LikeCounter::where('likable_type', 'App\Media')->where('likable_id', $media->id)->delete();
            $media->retag([]);
            $media->comments()->delete();
            $media->delete();
            $attachment->delete();

            // delete upload session and all files in it
            $mask = "$slug*.*";
            $directory = public_path('uploads/content/media/'.$upload_session);

            array_map('unlink', glob($directory.'/'.$mask));

            if (count(glob("$directory/*")) === 0) {
                rmdir($directory);
            }

            flash('Media has been deleted!', 'success');

            $page = 'index';
            $user = User::where('username', $username)->firstOrFail();
            $data = Media::where('user_id', $user->id)->latest()->paginate(config('media_per_page'));

            return view('profile', compact('user', 'page', 'data', 'users', 'owner'));
        } else {
            return abort(403);
        }
    }

    public function makeWaterMark($file, $put_path)
    {
        $img = IMG::make($file);
        $img->insert('assets/images/watermark.png', 'bottom-right', 10, 10);
        $img->save('uploads/content/media/'.$put_path);
    }

    public function putLike(Request $request)
    {
        $media = Media::findOrFail($request->input('id'));

        if ($media->liked()) {
            $media->unlike();
            Event::fire('add.points.media_remove_like', $media->user_id);
        } else {
            $media->like();
            Event::fire('add.points.media_get_like', $media->user_id);
        }

        // ( $media->liked() ? $media->unlike() : $media->like());
        return response()->json($media->likeCount);
    }

    public function putComment(Request $request)
    {
        $media = Media::with('post')->find($request->media_id);
        $post = $media->post;
        $post->comments()->create([
                'media_id' => $media->id,
                'body'     => $request->body,
                'user_id'  => \Auth::id()
            ]);

        // empty means user deleted himself
        if (empty($user)) {
            $user = User::find(1);
        }

        // Check if user would like to be notified
/*        if ($user->notification_comments == 1) {
            $user_email = $user->email;

            $data = ['user' => $user, 'media' => $media];
            $array = [
                'user_email' => $user_email,
                'media_type' => $media->type,
            ];

            if (! str_contains($array['user_email'], request()->getHost()) && config('sparkpost_secret')) {
                Mail::send('emails.new_comment_posted', $data, function ($message) use ($array) {
                    if (config('no_reply_email')) {
                        $message
                            ->from(config('no_reply_email'), config('website_title'))
                            ->to($array['user_email'])
                            ->subject('Your '.ucfirst(($array['media_type'] != 'audio') ? $array['media_type'] : $array['media_type'].' File ').' Received A New Comment!');
                    } else {
                        $message
                            ->to($array['user_email'])
                            ->subject('Your '.ucfirst(($array['media_type'] != 'audio') ? $array['media_type'] : $array['media_type'].' File ').' Received A New Comment!');
                    }
                });
            }
        }*/

        Event::fire('add.points.add_comment', $request->user()->id);

        return redirect($this->getRedirectUrl() . '#comments');
    }

    public function putCommentLike(Request $request)
    {
        $comment = Comment::findOrFail($request->input('id'));

        if ($comment->liked()) {
            $comment->unlike();
            Event::fire('add.points.comment_remove_like', $request->user()->id);
        } else {
            $comment->like();
            Event::fire('add.points.comment_get_like', $request->user()->id);
        }

        //( $comment->liked() ? $comment->unlike() : $comment->like());
        return response()->json($comment->likeCount);
    }

    public function imageHandler($local_path, $upload_session, $new_file_name, $extension, $domain)
    {
        $type = 'picture';
        // create a thumbnail for the JSON return
        $width = 80;
        $height = 80;

        $path = "$local_path$upload_session/$new_file_name.$extension";

        $thumbnail_sm = IMG::make($path);
        $thumbnail_sm->height() > $thumbnail_sm->width() ? $width = null : $height = null;
        $thumbnail_sm->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        });
        $thumbnail_sm->save($local_path.$upload_session.'/'.$new_file_name.'-thumbnail-sm.'.$extension);

        $width = 400;
        $height = 300;

        $thumbnail_l = IMG::make($path);
        if ($thumbnail_l->height() > $height || $thumbnail_l->width() > $width) {
            $thumbnail_l->height() > $thumbnail_l->width() ? $width = null : $height = null;
            $thumbnail_l->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
            });
        }
        $thumbnail_l->save($local_path.$upload_session.'/'.$new_file_name.'-thumbnail-l.'.$extension, 60);

        $output = [[
            'thumbnail_url' => $domain.$local_path.$upload_session.'/'.$new_file_name.'-thumbnail-sm.'.$extension,
            'type'          => $type,
        ]];

        return $output;
    }

    public function audioHandler($local_path, $upload_session, $new_file_name, $extension, $domain)
    {
        $type = 'audio';
        $path = getcwd();
        $full_audio_path = $path.'/'.$local_path.$upload_session.'/'.$new_file_name.'.'.$extension;

        // Get the audio duration
        $duration = $this->getDuration($full_audio_path);

        $thumbnail_url = $domain.'assets/images/soundwave.png';

        $output = [[
            'thumbnail_url' => $thumbnail_url,
            'type'          => $type,
            'duration'      => $duration,
        ]];

        return $output;
    }

    public function applicationAndTexHandler($mime_type, $extension, $domain)
    {
        if (str_contains($mime_type, 'application')) {
            $type = 'application';
        } else {
            $type = 'text';
        }

        $path = getcwd();
        $local_path = 'assets/images/file-types/'.$extension.'.png';
        $check = $path.'/'.$local_path;

        $word = ['doc', 'dotx', 'docm', 'docx'];
        $powerpoint = ['ppt', 'pot', 'pps', 'pptx', 'pptm', 'potm',
            'potx', 'ppam', 'ppsx', 'ppsm', 'sldx', 'sldm', ];
        $excel = ['xls', 'xls', 'xlsx', 'xlsm', 'xltx', 'xltm'];
        $html = ['html', 'htm', 'xhtml'];
        $code = ['m', 'c', 'js', 'sass', 'scss'];

        if (in_array($extension, $word)) {
            $thumbnail_url = $domain.'assets/images/file-types/word.png';
        } elseif (in_array($extension, $powerpoint)) {
            $thumbnail_url = $domain.'assets/images/file-types/powerpoint.png';
        } elseif (in_array($extension, $excel)) {
            $thumbnail_url = $domain.'assets/images/file-types/excel.png';
        } elseif (in_array($extension, $html)) {
            $thumbnail_url = $domain.'assets/images/file-types/html.png';
        } elseif (in_array($extension, $code)) {
            $thumbnail_url = $domain.'assets/images/file-types/code.png';
        } elseif (file_exists($check)) {
            $thumbnail_url = $domain.$local_path;
        } else {
            $thumbnail_url = $domain.'assets/images/application-sm.png';
        }

        $output = [[
            'thumbnail_url' => $thumbnail_url,
            'type'          => $type,
        ]];

        return $output;
    }

    /*
    * first, we create a thumbnail for the homepage (large thumbnail) and upload page (small thumbnail)
    * second, we convert any video file to a mp4 web optimized format with HandBrakeCLI.
    */

    public function videoHandler($local_path, $upload_session, $new_file_name, $extension, $domain)
    {
        $ffmpeg_path = 'ffmpeg';
        $handbrake_path = 'HandBrakeCLI';
        $path = getcwd();
        $full_video_path = $path.'/'.$local_path.$upload_session.'/'.$new_file_name.'.'.$extension;
        $save_video_thumbnail = $path.'/'.$local_path.$upload_session.'/'.$new_file_name.'-thumbnail.jpg';
        $thumbnail_path = $upload_session.'/'.$new_file_name.'-thumbnail.jpg';

        // Get the movie duration
        $duration = $this->getDuration($full_video_path);

        $ffmpeg = command_exist('ffmpeg --help');
        $handbrake = command_exist('HandBrakeCLI --help');

        $shell_exec = isEnabled('shell_exec');
        $exec = isEnabled('exec');

        if ($ffmpeg && $exec) {
            $thumbnail_url = $this->createVideoThumbnails($duration, $ffmpeg_path, $full_video_path, $save_video_thumbnail, $local_path, $thumbnail_path, $upload_session, $new_file_name, $domain);
        } else {
            $thumbnail_url = $domain.'assets/images/video_file.png';
        }

        if ($ffmpeg && $handbrake && $shell_exec) {
            $job = (new ConvertVideoToStreamableFormat($extension, $path, $local_path, $upload_session, $new_file_name, $handbrake_path, $full_video_path, $ffmpeg_path))
                ->delay(Carbon::now()->addSeconds(5));

            dispatch($job);
        }

        $output = [[
            'thumbnail_url' => $thumbnail_url,
            'type'          => 'video',
            'duration'      => $duration,
        ]];

        return $output;
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

    public function sendEmailAboutNewMediaToFollowers($request, $handler_output, $new_file_name, $original_name)
    {
        $user = $request->user();

        $job = (new SendEmail('sendEmailAboutNewMediaToFollowers', $user, $original_name, $new_file_name, $handler_output))
            ->delay(Carbon::now()->addSeconds(15));

        dispatch($job);
    }

    public function store($upload_session, $new_file_name, $extension, $uploaded_file, $upload_type)
    {
        // if uploaded file is a string then it is the path of remote uploaded file
        if ($upload_type == 'remote' || $upload_type == 'clone') {
            $path = public_path('uploads/content/media/'.$upload_session.'/');
            if (!File::exists($path)) {
                File::makeDirectory($path);
            }
            File::move($uploaded_file, $path.$new_file_name.'.'.$extension);
        } else {
            // only store if it is not a chunked upload
            if(\App::environment('production')){
                Storage::disk('s3')->put('/'.$upload_session.'/'.$new_file_name.'.'.$extension, $uploaded_file);
            }else{
                Storage::disk('media')->put('/'.$upload_session.'/'.$new_file_name.'.'.$extension, $uploaded_file);
            }
        }
    }

    public function mediaHandler($mime_type, $local_path, $upload_session, $new_file_name, $extension, $domain)
    {
        $undetected_video_formats = ['mkv', 'ogm', 'mpeg4'];

        // when cloning the mime type is "picture"
        if (str_contains($mime_type, 'image') || str_contains($mime_type, 'picture')) {
            return $this->imageHandler($local_path, $upload_session, $new_file_name, $extension, $domain);
        } elseif (str_contains($mime_type, 'video') || in_array($extension, $undetected_video_formats)) {
            return $this->videoHandler($local_path, $upload_session, $new_file_name, $extension, $domain);
        } elseif (str_contains($mime_type, 'audio')) {
            return $this->audioHandler($local_path, $upload_session, $new_file_name, $extension, $domain);
        } elseif (str_contains($mime_type, 'application') || str_contains($mime_type, 'text')) {
            return $this->applicationAndTexHandler($mime_type, $extension, $domain);
        }
    }

    public function isMediaPrivate($request, $original_name, $extension)
    {
        $private_test = preg_replace("/[^\w]+/", '', $original_name);
        $private = $request->input($private_test.$extension);

        if (isset($private)) {
            return 1;
        } else {
            return 0;
        }
    }

    public function createMediaAttachment($handler_output, $original_name, $new_file_name, $extension, $file_size, $upload_session, $media)
    {
        if (isset($handler_output['duration'])) {
            $attachment_data = [
                'file_original_name'    => $original_name,
                'file_name'             => $new_file_name,
                'file_mime'             => $extension,
                'file_size'             => $file_size,
                'upload_session'        => $upload_session,
                'duration'              => $handler_output['duration'],
            ];
        } else {
            $attachment_data = [
                'file_original_name'    => $original_name,
                'file_name'             => $new_file_name,
                'file_mime'             => $extension,
                'file_size'             => $file_size,
                'upload_session'        => $upload_session,
            ];
        }

        $attachment_data = json_encode($attachment_data);

        return $media->attachments()->create([
            'content'        => $attachment_data,
            'upload_session' => $upload_session,
        ]);
    }

    public function createMedia($new_file_name, $request, $upload_session, $private, $domain, $handler_output, $response, $original_name, $extension, $file_size)
    {
        if (Auth::check()) {
            $user = Auth::user();
        } else {
            $user = User::find(1);
        }

        if ($user->type == 'publisher' || $user->type == 'admin') {
            $approved = 1;
        } else {
            $approved = config('auto_approve');
        }

        if (config('s3_active')) {
            $disk = 's3';
        } elseif (config('dropbox_active')) {
            $disk = 'dropbox';
        } else {
            $disk = 'local';
        }

        $request->merge([
            'approved'       => $approved,
            'slug'           => $new_file_name,
            'upload_session' => $upload_session,
            'private'        => $private,
            'type'           => $handler_output['type'],
            'title'          => $original_name,
            'category_id'    => 5, // 5 is default / other category
            'ip'             => request()->ip(),
            'key'            => str_random(6),
            'disk'           => $disk
        ]);

        // create new media
        if (Auth::check()) {
            // Start transaction
            DB::beginTransaction();

            // $media = $request->user()->media()->create($request->all());
            // dd($request->all());
            $media = new Media($request->except('team_id'));
            $media->user_id = \Auth::id();
            $media->save();
            // $this->sendIdToPost($media->id);

            // Handle Point Event
            Event::fire('add.points.upload_media', $request->user()->id);

            $this->sendEmailAboutNewMediaToFollowers($request, $handler_output, $new_file_name, $original_name);
        } else {
            // 0 user_id equals the guest account
            Event::fire('add.points.upload_media', 1);

            $media = Media::create($request->all());
        }

        // create media attachment
        $attachments = $this->createMediaAttachment($handler_output, $original_name, $new_file_name, $extension, $file_size, $upload_session, $media);

        // attach tags
        /*if ($request->input('tags') != '') {
            $media->retag($request->input('tags'));
        }*/

        $this->sendEmailAboutUploadToAdmin($request, $original_name, $new_file_name);

        // $response is set to false when it is not a direct upload
        if ($response) {
            if (!$attachments) {
                DB::rollback();
            } else {
                DB::commit();
                array_push(
                    $response['files'],
                    [
                        'name'         => $original_name,
                        'size'         => $file_size,
                        'url'          => $domain.'media/'.$new_file_name,
                        'thumbnailUrl' => $handler_output['thumbnail_url'],
                        'deleteUrl'    => '',
                        'deleteType'   => '',
                        'media'        => $media
                    ]
                );
            }

            // upload the $upload_session folder with all of its content to S3
            if (config('s3_active')) {
                $job = (new UploadToExternalDisk($upload_session, 's3'))
                    ->delay(Carbon::now()->addSeconds(15));

                dispatch($job);
            } elseif (config('dropbox_active')) {
                $job = (new UploadToExternalDisk($upload_session, 'dropbox'))
                    ->delay(Carbon::now()->addSeconds(15));

                dispatch($job);
            }
            // return json_encode($response);
            return $media;
        } else {
            // it is a remote upload
            if (config('s3_active')) {
                $job = (new UploadToExternalDisk($upload_session, 's3'))
                    ->delay(Carbon::now()->addSeconds(15));

                dispatch($job);
            } elseif (config('dropbox_active')) {
                $job = (new UploadToExternalDisk($upload_session, 'dropbox'))
                    ->delay(Carbon::now()->addSeconds(15));

                dispatch($job);
            }

            if (! $attachments) {
                DB::rollback();
            } else {
                DB::commit();
            }
        }
    }

    /*
     * create the upload session folder if it doesn't exist
     * then move final file from storage/app/chunks
     * to public/uploads/content/media/$upload_session
     * and rename to actual name
     */

    public function moveChunkedFileToPublic($local_path, $upload_session, $chunk_file_name, $new_file_name, $extension)
    {
        $upload_session_path = public_path("$local_path$upload_session");
        if (!File::exists($upload_session_path)) {
            // path does not exist
            File::makeDirectory($upload_session_path);
        }
        $chunk_file_path = storage_path("app/chunks/$chunk_file_name");
        $chunk_move_path = public_path("$local_path$upload_session/$new_file_name.$extension");

        File::move($chunk_file_path, $chunk_move_path);
    }

    public function classicRemoteUpload($request, $upload_session, $local_path, $domain)
    {
        // array of URLs. Each URL starts at a new line
        $urls = $request->input('url_list');

        // if private is checked, then media cannot be found on website and only be shared by link
        // $request->input('private_test') is returned as a string and not boolean
        $private = ($request->input('private_test') == "true") ? 1 : 0;

        if (!is_array($urls)) {
            throw new UploadMissingFileException();
        }

        foreach ($urls as $url) {
            if (filter_var($url, FILTER_VALIDATE_URL)) {
                $extension = pathinfo($url, PATHINFO_EXTENSION);
                // in case the extension includes a query parameter
                $extension = str_contains($extension, '?') ? substr($extension, 0, str_contains($extension, '?')) : $extension;

                $original_name = pathinfo($url, PATHINFO_FILENAME);
                $original_name = str_replace('%20', '', $original_name);

                $new_file_name = make_slug($original_name).'-'.rand(000, 999);

                $path = storage_path('app/remote-uploads/'.$new_file_name.'.'.$extension);

                // dd($new_file_name);

                $my_file = fopen($path, 'w');

                // use Guzzle to download remote file
                $client = new Client();
                $client->get($url, ['sink' => $my_file]);

                $file_size = filesize($path);

                // http://stackoverflow.com/questions/2754713/why-some-mp3s-on-mime-content-type-return-application-octet-stream
                if ($extension == 'mp3') {
                    $mime_type = 'audio/mp3';
                } else {
                    $mime_type = mime_content_type($path);
                }

                $upload_type = 'remote';

                $this->store($upload_session, $new_file_name, $extension, $path, $upload_type);

                // return thumbnail_url, type and duration and call videoHandler, imageHandler etc, to convert as well
                $handler_output = $this->mediaHandler($mime_type, $local_path, $upload_session, $new_file_name, $extension, $domain);

                $response = false;

                // make appropriate media database entries
                $this->createMedia($new_file_name, $request, $upload_session, $private, $domain, $handler_output[0], $response, $original_name, $extension, $file_size);

                if ($original_name) {
                    $remote_or_clone = "Remote";
                    $this->sendEmailAboutRemoteUploadFinished($request, $original_name, $new_file_name, $remote_or_clone);
                }
            }
        }
    }

    public function gdriveRemoteUpload($request, $upload_session, $local_path, $domain)
    {
        // array of URLs/Google Drive IDs.
        $urls = $request->input('url_list');

        // if private is checked, then media cannot be found on website and only be shared by link
        $private = ($request->input('private_test') == "true") ? 1 : 0;

        foreach ($urls as $id) {
            //0BygTO8aWqs9YWnJqSXRKTE9nUlE
            //https://drive.google.com/open?id=0BygTO8aWqs9YWnJqSXRKTE9nUlE
            //https://drive.google.com/file/d/0BygTO8aWqs9YWnJqSXRKTE9nUlE/view
            if (filter_var($id, FILTER_VALIDATE_URL)) {
                // get ID from URL
                parse_str($id, $params);

                if (! reset($params)) {
                    $url_parts = explode('/', $id);
                    $id = $url_parts[5];
                } else {
                    // get the first array element
                    $id = reset($params);
                }
            }

            if (command_exist('gdrive help') && isEnabled('shell_exec')) {
                // download the file and save it to remote-upload storage
                $cmd = 'cd ../storage/app/remote-uploads/; gdrive download "'.$id.'"';
                shell_exec($cmd);

                // get the file info - use shell_exec to get the whole output
                $cmd = 'gdrive info "'.$id.'"';
                $output = shell_exec($cmd);

                $matches = array();
                preg_match('/Name:(.*?)\n/', $output, $matches);
                $name = trim(basename($matches[1])); // with extension

                $extension = pathinfo($name, PATHINFO_EXTENSION);
                $original_name = pathinfo($name, PATHINFO_FILENAME); // without extension

                $matches = array();
                preg_match('/Mime:(.*?)\n/', $output, $matches);
                $mime_type = trim($matches[1]);

                $new_file_name = make_slug($original_name).'-'.rand(000, 999);

                $old_name = storage_path('app/remote-uploads/'.$original_name.'.'.$extension);
                $path = storage_path('app/remote-uploads/'.$new_file_name.'.'.$extension);

                // rename the file, because gdrive is downloaded and stored as original filename
                File::move($old_name, $path);

                $file_size = filesize($path);

                $upload_type = 'remote';

                $this->store($upload_session, $new_file_name, $extension, $path, $upload_type);

                // return thumbnail_url, type and duration and call videoHandler, imageHandler etc, to convert as well
                $handler_output = $this->mediaHandler($mime_type, $local_path, $upload_session, $new_file_name, $extension, $domain);

                $response = false;

                // make appropriate media database entries
                $this->createMedia($new_file_name, $request, $upload_session, $private, $domain, $handler_output[0], $response, $original_name, $extension, $file_size);

                if ($original_name) {
                    $remote_or_clone = 'Remote';
                    $this->sendEmailAboutRemoteUploadFinished($request, $original_name, $new_file_name, $remote_or_clone);
                }
            } else {
                $url = 'https://docs.google.com/uc?id='.$id.'&export=download';

                $extension = pathinfo($url, PATHINFO_EXTENSION);
                // in case the extension includes a query parameter
                $extension = str_contains($extension, '?') ? substr($extension, 0, str_contains($extension, '?')) : $extension;

                $original_name = pathinfo($url, PATHINFO_FILENAME);
                $original_name = str_replace('%20', '', $original_name);

                $new_file_name = make_slug($original_name).'-'.rand(000, 999);

                $path = storage_path('app/remote-uploads/'.$new_file_name.'.'.$extension);

                // dd($new_file_name);

                $my_file = fopen($path, 'w');

                // use Guzzle to download remote file
                $client = new Client();
                $client->get($url, ['sink' => $my_file]);

                $file_size = filesize($path);

                // http://stackoverflow.com/questions/2754713/why-some-mp3s-on-mime-content-type-return-application-octet-stream
                if ($extension == 'mp3') {
                    $mime_type = 'audio/mp3';
                } else {
                    $mime_type = mime_content_type($path);
                }

                $upload_type = 'remote';

                $this->store($upload_session, $new_file_name, $extension, $path, $upload_type);

                // return thumbnail_url, type and duration and call videoHandler, imageHandler etc, to convert as well
                $handler_output = $this->mediaHandler($mime_type, $local_path, $upload_session, $new_file_name, $extension, $domain);

                $response = false;

                // make appropriate media database entries
                $this->createMedia($new_file_name, $request, $upload_session, $private, $domain, $handler_output[0], $response, $original_name, $extension, $file_size);

                if ($original_name) {
                    $remote_or_clone = "Remote";
                    $this->sendEmailAboutRemoteUploadFinished($request, $original_name, $new_file_name, $remote_or_clone);
                }
            }
        }
    }

    public function dropboxRemoteUpload($request, $upload_session, $local_path, $domain)
    {
        // array of URLs. Each URL starts at a new line
        $urls = $request->input('url_list');

        // if private is checked, then media cannot be found on website and only be shared by link
        // $request->input('private_test') is returned as a string and not boolean
        $private = ($request->input('private_test') == "true") ? 1 : 0;

        if (!is_array($urls)) {
            throw new UploadMissingFileException();
        }

        foreach ($urls as $url) {
            if (filter_var($url, FILTER_VALIDATE_URL)) {
                $url = str_replace('dl=0', 'dl=1', $url);

                $extension = pathinfo($url, PATHINFO_EXTENSION);
                // in case the extension includes a query parameter
                $extension = str_contains($extension, '?') ? substr($extension, 0, str_contains($extension, '?')) : $extension;

                $original_name = pathinfo($url, PATHINFO_FILENAME);
                $original_name = str_replace('%20', '', $original_name);

                $new_file_name = make_slug($original_name).'-'.rand(000, 999);

                $path = storage_path('app/remote-uploads/'.$new_file_name.'.'.$extension);

                // dd($new_file_name);

                $my_file = fopen($path, 'w');

                // use Guzzle to download remote file
                $client = new Client();
                $client->get($url, ['sink' => $my_file]);

                $file_size = filesize($path);

                // http://stackoverflow.com/questions/2754713/why-some-mp3s-on-mime-content-type-return-application-octet-stream
                if ($extension == 'mp3') {
                    $mime_type = 'audio/mp3';
                } else {
                    $mime_type = mime_content_type($path);
                }

                $upload_type = 'remote';

                $this->store($upload_session, $new_file_name, $extension, $path, $upload_type);

                // return thumbnail_url, type and duration and call videoHandler, imageHandler etc, to convert as well
                $handler_output = $this->mediaHandler($mime_type, $local_path, $upload_session, $new_file_name, $extension, $domain);

                $response = false;

                // make appropriate media database entries
                $this->createMedia($new_file_name, $request, $upload_session, $private, $domain, $handler_output[0], $response, $original_name, $extension, $file_size);

                if ($original_name) {
                    $remote_or_clone = 'Remote';
                    $this->sendEmailAboutRemoteUploadFinished($request, $original_name, $new_file_name, $remote_or_clone);
                }
            }
        }
    }

    public function mediaHostsRemoteUpload($request, $upload_session, $local_path, $domain)
    {
        // array of URLs/Google Drive IDs.
        $urls = $request->input('url_list');
        $urls = array_slice($urls, 0, 5);

        // if private is checked, then media cannot be found on website and only be shared by link
        $private = ($request->input('private_test') == "true") ? 1 : 0;

        foreach ($urls as $url) {
            if (filter_var($url, FILTER_VALIDATE_URL)) {
                // check if it is Openload URL
                if (str_contains($url, 'openload')) {
                    $this->openloadDownload($request, $url, $private, $upload_session, $local_path, $domain);
                } else {
                    // download the file and save it to remote-upload storage
                    // last line of output is filename with extension
                    $user_agent = $request->header('User-Agent');

                    $cmd = 'cd ../storage/app/remote-uploads/; youtube-dl "'.$url.'" -r 3.0M --user-agent "' . $user_agent . '"; youtube-dl "'.$url.'" --get-filename';
                    $name = exec($cmd);

                    $extension = pathinfo($name, PATHINFO_EXTENSION);
                    $extension = str_contains($extension, '?') ? substr($extension, 0, str_contains($extension, '?')) : $extension;

                    $original_name = pathinfo($name, PATHINFO_FILENAME); // without extension

                    $old_name = storage_path('app/remote-uploads/'.$original_name.'.'.$extension);

                    $new_file_name = make_slug($original_name).'-'.rand(000, 999);
                    $path = storage_path('app/remote-uploads/'.$new_file_name.'.'.$extension);

                    // rename the file, because youtube-dl file is downloaded and stored as original filename
                    File::move($old_name, $path);

                    $file_size = filesize($path);

                    // http://stackoverflow.com/questions/2754713/why-some-mp3s-on-mime-content-type-return-application-octet-stream
                    if ($extension == 'mp3') {
                        $mime_type = 'audio/mp3';
                    } else {
                        $mime_type = mime_content_type($path);
                    }

                    $upload_type = 'remote';

                    $this->store($upload_session, $new_file_name, $extension, $path, $upload_type);

                    // return thumbnail_url, type and duration and call videoHandler, imageHandler etc, to convert as well
                    $handler_output = $this->mediaHandler($mime_type, $local_path, $upload_session, $new_file_name, $extension, $domain);

                    $response = false;

                    // make appropriate media database entries
                    $this->createMedia($new_file_name, $request, $upload_session, $private, $domain, $handler_output[0], $response, $original_name, $extension, $file_size);

                    if ($original_name) {
                        $remote_or_clone = 'Remote';
                        $this->sendEmailAboutRemoteUploadFinished($request, $original_name, $new_file_name, $remote_or_clone);
                    }
                }
            }
        }
    }

    public function cloneRemoteUpload($request)
    {
        // array of URLs. Each URL starts at a new line
        $urls = $request->input('url_list');

        // if private is checked, then media cannot be found on website and only be shared by link
        // $request->input('private_test') is returned as a string and not boolean
        $private = ($request->input('private_test') == "true") ? 1 : 0;

        if (!is_array($urls)) {
            throw new UploadMissingFileException();
        }

        foreach ($urls as $url) {
            if (filter_var($url, FILTER_VALIDATE_URL)) {
                // https://clooud.tv/media/big-buck-bunny-240p-5mb3gpmp4-tr6gjoozmj0-391
                $array = explode('/', $url);
                $slug_or_key = end($array);
                $row = 'slug';

                $len = strlen($slug_or_key);
                if ($len == 6 || $len == 7) {
                    $row = 'key';
                }

                $media = Media::where($row, $slug_or_key)->firstOrFail();

                $new_file_name = make_slug($media->title).'-'.rand(000, 999);

                $user_id = 0;
                if (Auth::check()) {
                    $user_id = Auth::id();
                }

                if (! isset($media->password)) {
                    Media::create([
                        'slug'           => $new_file_name,
                        'category_id'    => $media->category_id,
                        'title'          => $media->title,
                        'user_id'        => $user_id,
                        'type'           => $media->type,
                        'body'           => $media->body,
                        'upload_session' => $media->upload_session,
                        'ip'             => $request->ip(),
                        'key'            => str_random(6),
                        'cloned'         => $media->id,
                        'private'        => $private,
                    ]);

                    $this->sendEmailAboutRemoteUploadFinished($request, $media->title, $new_file_name, 'Clone');
                } else {
                    abort(401);
                }
            }
        }
    }

    public function sendEmailAboutRemoteUploadFinished($request, $original_name, $new_file_name, $remote_or_clone)
    {
        $user = $request->user();

        $job = (new SendEmail('sendEmailAboutRemoteUploadFinished', $user, $original_name, $new_file_name, null, $remote_or_clone))
            ->delay(Carbon::now()->addSeconds(15));

        dispatch($job);
    }

    public function updateThumbnail($media, $request)
    {
        // update the thumbnail
        $upload_session = $media->upload_session;
        $slug = $media->slug;
        $path = 'uploads/content/media/'.$upload_session.'/';
        $thumbnail = 'uploads/content/media/'.$upload_session.'/'.$slug.'-thumbnail.jpg';
        $thumbnail_l = 'uploads/content/media/'.$upload_session.'/'.$slug.'-thumbnail-l.jpg';

        if (File::exists($thumbnail)) {
            File::delete($thumbnail);
            File::delete($thumbnail_l);
            $request->file('thumbnail')->move(public_path($path), $slug.'-thumbnail.jpg');

            $width = 800;
            $height = 600;

            $video_thumbnail_l = IMG::make(public_path($thumbnail));
            $video_thumbnail_l->height() > $video_thumbnail_l->width() ? $width = null : $height = null;
            $video_thumbnail_l->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
            });
            $video_thumbnail_l->save(public_path($thumbnail_l), 60);
        } else {
            // this should never be called, just in case
            $request->file('thumbnail')->move(public_path($path), $slug.'-thumbnail-l.jpg');

            $width = 800;
            $height = 600;

            $video_thumbnail_l = IMG::make(public_path($thumbnail));
            $video_thumbnail_l->height() > $video_thumbnail_l->width() ? $width = null : $height = null;
            $video_thumbnail_l->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
            });
            $video_thumbnail_l->save(public_path($thumbnail_l), 60);
        }
    }

    public function sendEmailAboutUploadToAdmin($request, $original_name, $new_file_name)
    {
        $user = $request->user();

        $job = (new SendEmail('sendEmailAboutUploadToAdmin', $user, $original_name, $new_file_name))
            ->delay(Carbon::now()->addSeconds(15));

        dispatch($job);
    }

    public function approve($id, $approve)
    {
        if (Auth::user()->type == 'admin') {
            $media = Media::where('id', $id)->first();

            if ($approve == 1) {
                $media->update(['approved' => 1]);

                return response()->json(1, 200);
            }

            return response()->json(0, 200);
        } else {
            return abort(403);
        }
    }

    /*
     * This function does not download the videos from Openload
     * but rather links to the direct download file
     */
//    public function openloadClone($request, $url, $upload_session, $private, $domain, $local_path)
//    {
//        $openloadjs = public_path('assets/js/openload.js');
//        $cmd = "phantomjs $openloadjs $url";
//        $stream_url = trim(str_replace(' ', '%20', shell_exec($cmd)));
//        $name = trim(explode('|', str_replace('%20', ' ', $stream_url))[1]);
//
//        $ch = curl_init($stream_url);
//        curl_setopt($ch, CURLOPT_NOBODY, true);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//        curl_setopt($ch, CURLOPT_HEADER, true);
//        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
//        curl_setopt($ch, CURLOPT_USERAGENT, $request->header('User-Agent'));
//
//        curl_exec($ch);
//
//        $file_size = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
//        $mime_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
//
//        curl_close($ch);
//
//        if (str_contains($mime_type, ['video', 'stream'])) {
//            $extension = pathinfo($name, PATHINFO_EXTENSION);
//            // without the extension
//            $original_name = pathinfo($name, PATHINFO_FILENAME);
//
//            $original_name = str_replace('_', ' ', $original_name);
//
//            // since each video file gets converted by OL to mp4, the name will include the original extension
//            $original_name = pathinfo($original_name, PATHINFO_FILENAME);
//
//            $new_file_name = make_slug($original_name).'-'.rand(000, 999);
//
//            $response = false;
//
//            $ffmpeg_path = 'ffmpeg';
//
//            // Get the duration
//            $parameters = "2>&1 | grep Duration | cut -d ' ' -f 4 | sed s/,//";
//            $cmd_duration_ffmpeg = "$ffmpeg_path -i $stream_url -headers '".$request->header('User-Agent')."' $parameters";
//            $duration = shell_exec($cmd_duration_ffmpeg);
//
//            echo $cmd_duration_ffmpeg;
//            dd($duration);
//
//            $upload_session_path = public_path('uploads/content/media/'.$upload_session.'/');
//            if (! File::exists($upload_session_path)) {
//                File::makeDirectory($upload_session_path);
//            }
//
//            $path = getcwd();
//            $save_video_thumbnail = $path.'/'.$local_path.$upload_session.'/'.$new_file_name.'-thumbnail.jpg';
//            $thumbnail_path = $upload_session.'/'.$new_file_name.'-thumbnail.jpg';
//
//            $thumbnail_url = $this->createVideoThumbnails($duration, $ffmpeg_path, $stream_url, $save_video_thumbnail, $local_path, $thumbnail_path, $upload_session, $new_file_name, $domain);
//
//            $handler_output = array('type' => 'video', 'duration' => $duration, 'thumbnail_url' => $thumbnail_url);
//
//            // make appropriate media database entries
//            $this->createMedia($new_file_name, $request, $upload_session, $private, $domain, $handler_output, $response, $original_name, $extension, $file_size);
//            Media::where('slug', $new_file_name)->update(['cloned' => $stream_url]);
//        } else {
//            // download the file and save it to remote-upload storage
//            // last line of output is filename with extension
//            $cmd = 'cd ../storage/app/remote-uploads/; youtube-dl "'.$url.'" -r 3.0M; youtube-dl "'.$url.'" --get-filename';
//            $name = exec($cmd);
//
//            $extension = pathinfo($name, PATHINFO_EXTENSION);
//            $extension = str_contains($extension, '?') ? substr($extension, 0, str_contains($extension, '?')) : $extension;
//
//            $original_name = pathinfo($name, PATHINFO_FILENAME); // without extension
//
//            $old_name = storage_path('app/remote-uploads/'.$original_name.'.'.$extension);
//
//            $new_file_name = make_slug($original_name).'-'.rand(000, 999);
//            $path = storage_path('app/remote-uploads/'.$new_file_name.'.'.$extension);
//
//            // rename the file, because youtube-dl file is downloaded and stored as original filename
//            File::move($old_name, $path);
//
//            $file_size = filesize($path);
//
//            // http://stackoverflow.com/questions/2754713/why-some-mp3s-on-mime-content-type-return-application-octet-stream
//            if ($extension == 'mp3') {
//                $mime_type = 'audio/mp3';
//            } else {
//                $mime_type = mime_content_type($path);
//            }
//
//            $upload_type = 'remote';
//
//            $this->store($upload_session, $new_file_name, $extension, $path, $upload_type);
//
//            // return thumbnail_url, type and duration and call videoHandler, imageHandler etc, to convert as well
//            $handler_output = $this->mediaHandler($mime_type, $local_path, $upload_session, $new_file_name, $extension, $domain);
//
//            $response = false;
//
//            // make appropriate media database entries
//            $this->createMedia($new_file_name, $request, $upload_session, $private, $domain, $handler_output[0], $response, $original_name, $extension, $file_size);
//
//            if ($original_name) {
//                $remote_or_clone = 'Remote';
//                $this->sendEmailAboutRemoteUploadFinished($request, $original_name, $new_file_name, $remote_or_clone);
//            }
//        }
//    }

    public function openloadDownload($request, $url, $private, $upload_session, $local_path, $domain)
    {
        $openloadjs = public_path('assets/js/openload.js');
        $cmd = "phantomjs $openloadjs $url";
        $url = trim(str_replace(' ', '%20', shell_exec($cmd)));
        $name = trim(explode('|', str_replace('%20', ' ', $url))[1]);

        $extension = pathinfo($name, PATHINFO_EXTENSION);

        $original_name = pathinfo($name, PATHINFO_FILENAME);
        $file_name_without_ext = pathinfo($original_name, PATHINFO_FILENAME);
        $original_name = str_replace('%20', '', $original_name);

        $new_file_name = make_slug($file_name_without_ext).'-'.rand(000, 999);

        $path = storage_path('app/remote-uploads/'.$new_file_name.'.'.$extension);

        // dd($new_file_name);

        $my_file = fopen($path, 'w');

        // use Guzzle to download remote file
        $client = new Client();
        $client->get($url, ['sink' => $my_file]);

        $file_size = filesize($path);

        // http://stackoverflow.com/questions/2754713/why-some-mp3s-on-mime-content-type-return-application-octet-stream
        if ($extension == 'mp3') {
            $mime_type = 'audio/mp3';
        } else {
            $mime_type = mime_content_type($path);
        }

        $upload_type = 'remote';

        $this->store($upload_session, $new_file_name, $extension, $path, $upload_type);

        // return thumbnail_url, type and duration and call videoHandler, imageHandler etc, to convert as well
        $handler_output = $this->mediaHandler($mime_type, $local_path, $upload_session, $new_file_name, $extension, $domain);

        $response = false;

        // make appropriate media database entries
        $this->createMedia($new_file_name, $request, $upload_session, $private, $domain, $handler_output[0], $response, $original_name, $extension, $file_size);

        if ($original_name) {
            $remote_or_clone = 'Remote';
            $this->sendEmailAboutRemoteUploadFinished($request, $original_name, $new_file_name, $remote_or_clone);
        }
    }

    public function getDuration($full_video_path)
    {
        $getID3 = new \getID3;
        $file = $getID3->analyze($full_video_path);
        $playtime_seconds = $file['playtime_seconds'];
        $duration = date('H:i:s.v', $playtime_seconds);

        return $duration;
    }

    public function sendIdToPost($id){
        return $id;
    }
}
