<?php

namespace App\Http\Controllers\Admin;

use App\Comment;
use App\Http\Controllers\Controller;
use Conner\Likeable\Like;
use Conner\Likeable\LikeCounter;
use Illuminate\Http\Request;
use View;
use Yajra\Datatables\Datatables;

class CommentsController extends Controller
{
    public function __construct()
    {
        View::share('menu', 'comments');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        if ($request->ajax()) {
            $comments = (new Comment())->newQuery();

            return Datatables::of($comments)

                ->editColumn('username', function ($comments) {
                    return $comments->user->username;
                })

                ->editColumn('comment', function ($comments) {
                    return $comments->body;
                })
                ->editColumn('media', function ($comments) {
                    return $comments->media->title;
                })

                ->editColumn('created_at', function ($comments) {
                    return $comments->created_at->format('Y-m-d');
                })->editColumn('options', function ($comments) {
                    $show = route('media.show', $comments->media->slug);
                    $edit = route('comments.edit', $comments->id);
                    $delete_id = $comments->id;

                    $delete_button = '<button data-id="'.$delete_id.'" type="button" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i></button> ';

                    return '<a href="'.$show.'#comment-'.$comments->id.'" target="_blank" class="btn btn-sm btn-info"><i class="fa fa-eye"></i> </a> '.
                    '<a href="'.$edit.'" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> </a> '.
                    $delete_button;
                })

                ->make(true);
        }

        return view('admin.comments.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $comment = Comment::findOrFail($id);

        return view('admin.comments.edit_comment', compact('comment'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        Comment::findOrFail($id)->update($request->all());
        flash('Comment has been updated', 'success');

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $comment = Comment::findOrFail($id);
        Like::where('likable_type', 'App\Comment')->where('likable_id', $comment->id)->delete();
        LikeCounter::where('likable_type', 'App\Comment')->where('likable_id', $comment->id)->delete();
        $comment->delete();

        return response()->json('ok', 200);
    }
}
