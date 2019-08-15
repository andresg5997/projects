<?php

namespace App\Http\Controllers\User;

use App\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UploadMediaRequest;
use App\Media;
use Illuminate\Http\Request;
use Conner\Likeable\Like;
use Conner\Likeable\LikeCounter;
use Purifier;
use View;
use Yajra\Datatables\Datatables;

class MediaManageController extends Controller
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
            $media = (new Media())->newQuery()->where('user_id', \Auth::id());

            if ($request->exists('popular')) {
                $media->orderBy('views', 'desc');
            }

            if ($request->has('category')) {
                $media->where('category_id', $request->category);
            }

            return Datatables::of($media)

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
                ->editColumn('views', function ($media) {
                    return $media->views;
                })
                ->editColumn('plays', function ($media) {
                    return $media->plays;
                })
                ->editColumn('likes', function ($media) {
                    return $media->likes->count();
                })
                ->editColumn('comments', function ($media) {
                    return $media->comments->count();
                })
                ->editColumn('created_at', function ($media) {
                    return $media->created_at->format('M j, y - g:i:s a');
                })->editColumn('options', function ($media) {
                    $show = route('media.show', $media->slug);
                    $edit = route('manage.edit', $media->id);
                    $media_id = $media->id;

                    if ($media->deleted_at) {
                        $delete_button = '<button data-id="'.$media_id.'" type="button" class="btn btn-success btn-xs"><i class="fa fa-recycle"></i></button> ';
                    } else {
                        $delete_button = '<button data-id="'.$media_id.'" type="button" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></button> ';
                    }

                    return '<a href="'.$show.'" target="_blank" class="btn btn-xs btn-info"><i class="fa fa-eye"></i> </a> '.
                    '<a href="'.$edit.'" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> </a> '.
                    $delete_button;
                })

                ->make(true);
        }

        $categories = Category::get(['id', 'name']);

        $tags = [];

        return view('media.manage.index', compact('categories', 'tags'));
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

        $private = $media->private;

        $anonymous = $media->anonymous;

        return view('media.manage.media_edit', compact('media', 'categories', 'tags', 'private', 'anonymous'));
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
        $private = ($request->input('private') !== null) ? 1 : 0;
        $anonymous = ($request->input('anonymous') !== null) ? 1 : 0;
        $body = Purifier::clean($request->input('body'));

        $request->merge([
            'private'   => $private,
            'anonymous' => $anonymous,
            'body'      => $body,
        ]);

        $media = Media::findOrFail($id);

        $media->update($request->all());
        if ($request->input('tags') != '') {
            $media->retag($request->input('tags'));
        } else {
            $media->retag([]);
        }

        flash('Media information has been updated!', 'success');

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
}
