<?php

namespace App\Http\Controllers\Admin;

use Artisan;
use Exception;
use Illuminate\Routing\Controller;
use League\Flysystem\Adapter\Local;
use Log;
use Request;
use Response;
use Storage;
use View;

class BackupController extends Controller
{
    public function __construct()
    {
        View::share('menu', 'advanced');
    }

    public function index()
    {
        if (!count(config('laravel-backup.backup.destination.disks'))) {
            dd('No Backup Disk configured');
        }

        $this->data['backups'] = [];

        foreach (config('laravel-backup.backup.destination.disks') as $disk_name) {
            if(\App::environment('production')){
                $disk = Storage::disk('s3');
            }
            else{
                $disk = Storage::disk($disk_name);
            }
            $adapter = $disk->getDriver()->getAdapter();
            $files = $disk->allFiles();

            // make an array of backup files, with their filesize and creation date
            foreach ($files as $k => $f) {
                // only take the zip files into account
                if (substr($f, -4) == '.zip' && $disk->exists($f)) {
                    $this->data['backups'][] = [
                        'file_path'     => $f,
                        'file_name'     => str_replace('backups/', '', $f),
                        'file_size'     => $disk->size($f),
                        'last_modified' => $disk->lastModified($f),
                        'disk'          => $disk_name,
                        'download'      => ($adapter instanceof Local) ? true : false,
                    ];
                }
            }
        }

        // reverse the backups, so the newest one would be on top
        $this->data['backups'] = array_reverse($this->data['backups']);
        $this->data['title'] = 'Backups';

        return view('admin.settings.advanced.backups', $this->data);
    }

    public function create()
    {
        try {
            ini_set('max_execution_time', 300);
          // start the backup process
          Artisan::call('backup:run');
            $output = Artisan::output();

          // log the results
          Log::info("Backpack\BackupManager -- new backup started from admin interface \r\n".$output);
          // return the results as a response to the ajax call
          echo $output;
        } catch (Exception $e) {
            Response::make($e->getMessage(), 500);
        }

        return 'success';
    }

    /**
     * Downloads a backup zip file.
     */
    public function download()
    {
        if(\App::environment('production')){
            $disk = Storage::disk('s3');
        }
        else{
            $disk = Storage::disk(Request::input('disk'));
        }
        $file_name = Request::input('file_name');
        $adapter = $disk->getDriver()->getAdapter();

        if ($adapter instanceof Local) {
            $storage_path = $disk->getDriver()->getAdapter()->getPathPrefix();

            if ($disk->exists($file_name)) {
                return response()->download($storage_path.$file_name);
            } else {
                abort(404, "The backup file doesn't exist");
            }
        } else {
            abort(404, 'Only downloads from the Local filesystem are supported.');
        }
    }

    /**
     * Deletes a backup file.
     */
    public function delete()
    {
        if(\App::environment('production')){
            $disk = Storage::disk('s3');
        }
        else{
            $disk = Storage::disk(Request::input('disk'));
        }
        $file_name = Request::input('file_name');
        $file_path = Request::input('path');

        if ($disk->exists($file_path)) {
            $disk->delete($file_path);

            return 'success';
        } else {
            abort(404, "The backup file doesn't exist.");
        }
    }
}
