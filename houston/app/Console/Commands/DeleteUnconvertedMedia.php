<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DeleteUnconvertedMedia extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'media:delete-unconverted';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes unconverted media files to save storage.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $folders = scandir(public_path('uploads/content/media'));

        foreach ($folders as $folder) {
            $mask = "*.*.mp4";
            $directory = public_path('uploads/content/media/'.$folder);

            if (glob($directory.'/'.$mask)) {
                $mask = "*.avi";
                print_r(array_map('unlink', glob($directory.'/'.$mask)));

                $mask = "*.mkv";
                print_r(array_map('unlink', glob($directory.'/'.$mask)));

                $mask = "*.3gp";
                print_r(array_map('unlink', glob($directory.'/'.$mask)));

                $mask = "*.flv";
                print_r(array_map('unlink', glob($directory.'/'.$mask)));

                $mask = "*.mov";
                print_r(array_map('unlink', glob($directory.'/'.$mask)));

                $mask = "*.mpg";
                print_r(array_map('unlink', glob($directory.'/'.$mask)));
            }
        }

        return "Deleted Unconverted Media files";
    }
}
