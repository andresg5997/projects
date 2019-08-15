<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ConvertVideoToStreamableFormat implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $extension, $path, $local_path, $upload_session, $new_file_name, $handbrake_path, $full_video_path, $ffmpeg_path;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($extension, $path, $local_path, $upload_session, $new_file_name, $handbrake_path, $full_video_path, $ffmpeg_path)
    {
        $this->extension = $extension;
        $this->path = $path;
        $this->local_path = $local_path;
        $this->upload_session = $upload_session;
        $this->new_file_name = $new_file_name;
        $this->handbrake_path = $handbrake_path;
        $this->ffmpeg_path = $ffmpeg_path;
        $this->full_video_path = $full_video_path;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->convertVideo($this->extension, $this->path, $this->local_path, $this->upload_session, $this->new_file_name, $this->handbrake_path, $this->full_video_path, $this->ffmpeg_path);
    }

    /*
   * Convert all uploaded videos
   * The reason we use HandBrake over FFMpeg is because of a mkv test file that had sound issues with ffmpeg
   * if it is a mp4 file already, use ffmpeg to optimize it for web, because no need to convert with it
   */
    public function convertVideo($extension, $path, $local_path, $upload_session, $new_file_name, $handbrake_path, $full_video_path, $ffmpeg_path)
    {
        if ($extension != 'mp4') {
            // $log = '2>&1 | tee -a conversion.log 2>/dev/null >/dev/null &';

            $converted_video = $path.'/'.$local_path.$upload_session.'/'.$new_file_name.'.'.$extension.'.mp4';
            $cmd_convert = "$handbrake_path -i $full_video_path -o $converted_video -m -e x264 -E copy -O";

            // echo 'cmd is '.$cmd_convert;
            //shell_exec($cmd_convert.' '.$log);
            shell_exec($cmd_convert);
            \File::delete(public_path('uploads/content/media/'.$upload_session.'/'.$new_file_name.'.'.$extension));
        } elseif ($extension == 'mp4') {
            // add wo, meaning "with optimization" for web display
            $converted_video = $path.'/'.$local_path.$upload_session.'/'.$new_file_name.'wo.mp4';
            $parameters = '-movflags faststart -acodec copy -vcodec copy';
            $cmd_optimize_mp4 = "$ffmpeg_path -y -i $full_video_path $parameters $converted_video";

            shell_exec($cmd_optimize_mp4);

            // delete original unoptimized mp4
            // then rename the optimized file to the original title
            unlink($full_video_path);

            $from = $path.'/'.$local_path.$upload_session.'/'.$new_file_name.'wo.mp4';
            $to = $path.'/'.$local_path.$upload_session.'/'.$new_file_name.'.mp4';

            rename($from, $to);
        } else {
            // any other uncommon video
            $converted_video = $path.'/'.$local_path.$upload_session.'/'.$new_file_name.'.'.$extension.'.mp4';
            $parameters = '-m -e x264 -E copy -O';
            $cmd_convert = "$handbrake_path -i $full_video_path -o $converted_video $parameters";

            shell_exec($cmd_convert.' 2>&1 | tee -a conversion.log 2>/dev/null >/dev/null &');
        }
    }
}
