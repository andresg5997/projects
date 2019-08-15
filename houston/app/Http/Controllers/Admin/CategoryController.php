<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use Conner\Likeable\Like;
use Conner\Likeable\LikeCounter;
use Illuminate\Http\Request;
use View;
use Yajra\Datatables\Datatables;

class CategoryController extends Controller
{
    public function __construct()
    {
        View::share('menu', 'categories');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $categories = (new Category())->newQuery();

            return Datatables::of($categories)

                ->editColumn('icon', function ($categories) {
                    return "<i class='fa ".$categories->icon."'></i>";
                })

                ->editColumn('name', '{!! str_limit($name, 30) !!}')

                ->addColumn('media', function ($categories) {
                    return $categories->media->count();
                })

                ->editColumn('created_at', function ($categories) {
                    return $categories->created_at->format('Y-m-d');
                })

                ->addColumn('options', function ($categories) {
                    $show = route('category.show', $categories->slug);
                    $edit = route('categories.edit', $categories->id);
                    $delete_id = $categories->id;
                    $delete_name = $categories->name;
                    $delete_posts = $categories->media->count();

                    return '<a href="'.$show.'" class="btn btn-sm btn-info"><i class="fa fa-eye"></i> </a> '.
                    '<a href="'.$edit.'" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> </a> '.
                    '<button data-id="'.$delete_id.'" data-media="'.$delete_posts.'" data-name="'.$delete_name.'" id="test" type="button" class="btn btn-danger btn-sm">
                       <i class="fa fa-trash-o"></i></button>';
                })

                ->make(true);
        }
        //
        return view('admin.categories.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.categories.category_create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {
        //
        if (trim($request->input('slug')) == '') {
            $request->merge(['slug'=> make_slug($request->input('name'))]);
        } else {
            $request->merge(['slug'=> make_slug($request->input('slug'))]);
        }
        Category::create($request->all());

        flash('Category has been created', 'success');

        return redirect()->route('categories.index');
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
        $category = Category::findOrFail($id);

        return view('admin.categories.category_edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $request, $id)
    {
        //
        if (trim($request->input('slug')) == '') {
            $request->merge(['slug'=> make_slug($request->input('name'))]);
        } else {
            $request->merge(['slug'=> make_slug($request->input('slug'))]);
        }

        Category::findOrFail($id)->update($request->all());

        flash('Category has been Updated', 'success');

        return redirect()->route('categories.index');
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
        $category = Category::findOrFail($id);

        // delete related comments
        $medias = $category->media;

        foreach ($medias as $media) {
            Like::where('likable_type', 'App\Media')->where('likable_id', $media->id)->delete();
            LikeCounter::where('likable_type', 'App\Media')->where('likable_id', $media->id)->delete();
            $media->retag([]);
            $media->attachments()->delete();
            $media->comments()->delete();
            $media->delete();
        }

        // delete Category
        $delete_category = $category->destroy($category->id);

        if (!$delete_category) {
            return response()->json($delete_category, 400);
        }

        return response()->json($delete_category, 200);
    }
}
