<?php

namespace App\Console\Commands;

use App\Media;

use Conner\Likeable\Like;
use Conner\Likeable\LikeCounter;
use Carbon\Carbon;

use Illuminate\Console\Command;

class DeleteOldMedia extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'media:delete-old';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes Old Media files which have not been viewed in x days.';

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
        //
        $delete_after_x_days = Carbon::now()->subDays(config('delete_after_x_days'));
        $medias = Media::where('created_at', '<=', $delete_after_x_days)
            ->where(function ($medias) use ($delete_after_x_days) {
                $medias->where('last_viewed_at', '<=', $delete_after_x_days)
                    ->orWhereNull('last_viewed_at');
            })
            ->get();

        foreach ($medias as $media) {
            Like::where('likable_type', 'App\Media')->where('likable_id', $media->id)->delete();
            LikeCounter::where('likable_type', 'App\Media')->where('likable_id', $media->id)->delete();
            $media->attachments()->delete();
            $media->retag([]);
            $media->comments()->delete();
            $media->delete();

            // delete upload session and all files in it
            $mask = "$media->slug*.*";
            $directory = public_path('uploads/content/media/'.$media->upload_session);

            array_map('unlink', glob($directory.'/'.$mask));

            if (count(glob("$directory/*")) === 0) {
                if (\File::exists($directory)) {
                    rmdir($directory);
                }
            }
        }

        return "Deleted Old Media files";
    }
}
