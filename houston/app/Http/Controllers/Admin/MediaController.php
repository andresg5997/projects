<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UploadMediaRequest;
use App\Media;
use App\User;
use Illuminate\Http\Request;
use Conner\Likeable\Like;
use Conner\Likeable\LikeCounter;
use View;
use Yajra\Datatables\Datatables;

class MediaController extends Controller
{
    public function __construct()
    {
        View::share('menu', 'media');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $media = (new Media())->newQuery();

            if ($request->exists('popular')) {
                $media->orderBy('views', 'desc');
            }

            if ($request->has('category')) {
                $media->where('category_id', $request->category);
            }

            return Datatables::of($media)

                ->editColumn('user', function ($media) {
                    return $media->user->username;
                })
                ->editColumn('thumb', function ($media) {
                    return "<img style='height:70px; width:100px;' src='".$media->previewImageUrl()."'/>";
                })
                ->editColumn('title', function ($media) {
                    $show = route('media.show', $media->slug);
                    return '<a href="'.$show.'" target="_blank" class="">'.str_limit($media->title, 30).'</a>';
                })
                ->editColumn('category', function ($media) {
                    return $media->category->name;
                })
                ->editColumn('comments', function ($media) {
                    return $media->comments->count();
                })
                ->editColumn('created_at', function ($media) {
                    return $media->created_at->format('h:i A');
                })->editColumn('options', function ($media) {
                    $show = route('media.show', $media->slug);
                    $edit = route('medias.edit', $media->id);
                    $media_id = $media->id;

                    if ($media->deleted_at) {
                        $delete_button = '<button data-id="'.$media_id.'" data-type="delete" type="button" class="btn btn-success btn-xs"><i class="fa fa-recycle"></i></button> ';
                    } else {
                        $delete_button = '<button data-id="'.$media_id.'" data-type="delete" type="button" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></button> ';
                    }

                    $approve_button = '';
                    if (! $media->approved) {
                        $approve_button = '<button data-id="'.$media_id.'" data-type="approve" type="button" class="btn btn-success btn-xs approved"><i class="fa fa-check"></i></button> ';
                    }

                    return '<a href="'.$show.'" target="_blank" class="btn btn-xs btn-info"><i class="fa fa-eye"></i> </a> '.
                    '<a href="'.$edit.'" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> </a> '.
                    $approve_button.
                    $delete_button;
                })

                ->make(true);
        }

        $categories = Category::get(['id', 'name']);

        $tags = [];

        return view('admin.media.index', compact('categories', 'tags'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        $media = Media::findOrFail($id);

        $tags = $media->tags->pluck('name')->toArray();

        $tags = implode(',', $tags);

        $categories = Category::pluck('name', 'id');

        return view('admin.media.media_edit', compact('media', 'categories', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UploadMediaRequest $request, $id)
    {
        //
        $media = Media::findOrFail($id);

        $media->update($request->all());
        if ($request->input('tags') != '') {
            $media->retag($request->input('tags'));
        } else {
            $media->retag([]);
        }

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
        $media = Media::find($id);
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
            rmdir($directory);
        }

        return response()->json('ok', 200);
    }

    public function approve($id)
    {
        $admin = User::where('id', \Auth::id())->where('type', 'admin')->count();

        if ($admin) {
            $media = Media::find($id);

            $media->update(['approved' => 1]);

            return response()->json(['success' => true]);
        } else {
            return abort(403);
        }
    }
}
