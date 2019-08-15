<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\File;

class UploadToExternalDisk implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $upload_session, $disk;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($upload_session, $disk)
    {
        //
        $this->upload_session = $upload_session;
        $this->disk = $disk;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // upload the $upload_session folder with all of its content to S3
        $files = \File::files(public_path('uploads/content/media/'.$this->upload_session));

        if (sizeof($files) > 0) {
            $disk = \Storage::disk($this->disk);

            foreach ($files as $file) {
                $path_info = pathinfo($file);
                $file_name = $path_info['filename'] . '.' . $path_info['extension'];

                if (! str_contains($path_info['extension'], ['mov', '3gp', 'avi', 'wmv', 'mng', 'mkv', 'flv', 'ogg', 'm4v', 'mpg', 'mpeg'])) {
                    $disk->put($this->upload_session . '/' . $file_name, file_get_contents($file));
                }
            }
        }

        if (! config('keep_copy')) {
            File::deleteDirectory(public_path('uploads/content/media/'.$this->upload_session));
        }
    }
}
