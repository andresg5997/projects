<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationsController extends Controller
{
    public function markAsRead($id){
    	$notification = Auth::user()->notifications()->find($id);
		  $notification->markAsRead();

		return redirect($notification->data['url']);
    }

    public function readAll()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return redirect()->back();
    }
}
