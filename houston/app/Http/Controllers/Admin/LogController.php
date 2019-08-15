<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Storage;
use View;

class LogController extends Controller
{
    public function __construct()
    {
        View::share('menu', 'advanced');
    }

    /**
     * Lists all log files.
     */
    public function index()
    {
        if(\App::environment('production')){
            $disk = Storage::disk('s3');
        }
        else{
            $disk = Storage::disk('storage');
        }
        $files = $disk->files('logs');
        $this->data['logs'] = [];

        // make an array of log files, with their filesize and creation date
        foreach ($files as $k => $f) {
            // only take the zip files into account
            if (substr($f, -4) == '.log' && $disk->exists($f)) {
                $this->data['logs'][] = [
                    'file_path'     => $f,
                    'file_name'     => str_replace('logs/', '', $f),
                    'file_size'     => $disk->size($f),
                    'last_modified' => $disk->lastModified($f),
                ];
            }
        }

        // reverse the logs, so the newest one would be on top
        $this->data['logs'] = array_reverse($this->data['logs']);
        $this->data['title'] = 'Log Manager';

        return view('admin.settings.advanced.logs', $this->data);
    }

    public function preview($file_name)
    {
        if(\App::environment('production')){
            $disk = Storage::disk('s3');
        }
        else{
            $disk = Storage::disk('storage');
        }

        if ($disk->exists('logs/'.$file_name)) {
            $this->data['log'] = [
                'file_path'     => 'logs/'.$file_name,
                'file_name'     => $file_name,
                'file_size'     => $disk->size('logs/'.$file_name),
                'last_modified' => $disk->lastModified('logs/'.$file_name),
                'content'       => trim($disk->get('logs/'.$file_name)),
            ];
            $this->data['title'] = "Preview Logs";

            return view('admin.settings.advanced.log_item', $this->data);
        } else {
            abort(404, "The log file doesn't exist.");
        }
    }

    public function download($file_name)
    {
        if(\App::environment('production')){
            $disk = Storage::disk('s3');
        }
        else{
            $disk = Storage::disk('storage');
        }

        if ($disk->exists('logs/'.$file_name)) {
            return response()->download(storage_path('logs/'.$file_name));
        } else {
            abort(404, "The log file doesn't exist.");
        }
    }

    public function delete($file_name)
    {
        if(\App::environment('production')){
            $disk = Storage::disk('s3');
        }
        else{
            $disk = Storage::disk('storage');
        }

        if ($disk->exists('logs/'.$file_name)) {
            $disk->delete('logs/'.$file_name);

            return 'success';
        } else {
            abort(404, "The log file doesn't exist.");
        }
    }
}
