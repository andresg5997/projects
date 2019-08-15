<?php

namespace App\Http\Controllers\Admin;

use \DevDojo\Chatter\Models\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use Illuminate\Http\Request;
use View;
use Yajra\Datatables\Datatables;

class ForumController extends Controller
{
    public function __construct()
    {
        View::share('menu', 'forum');
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

                ->editColumn('color', function ($categories) {
                    return "<input disabled type='color' value='".$categories->color."'>";
                })

                ->editColumn('name', '{!! str_limit($name, 30) !!}')

                ->editColumn('created_at', function ($categories) {
                    return $categories->created_at->format('Y-m-d');
                })

                ->addColumn('options', function ($categories) {
                    $show = route('chatter.category.show', $categories->slug);
                    $edit = route('forum.edit', $categories->id);
                    $delete_id = $categories->id;
                    $delete_name = $categories->name;

                    return '<a href="'.$show.'" class="btn btn-sm btn-info"><i class="fa fa-eye"></i> </a> '.
                    '<a href="'.$edit.'" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> </a> '.
                    '<button data-id="'.$delete_id.'" data-name="'.$delete_name.'" id="test" type="button" class="btn btn-danger btn-sm">
                       <i class="fa fa-trash-o"></i></button>';
                })

                ->make(true);
        }
        //
        return view('admin.forum.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.forum.category_create');
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

        if (empty($request->input('order'))) {
            $request->merge(['order' => 0]);
        }

        Category::create($request->all());

        flash('Category has been created', 'success');

        return redirect()->route('forum.index');
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

        return view('admin.forum.category_edit', compact('category'));
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

        return redirect()->route('forum.index');
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

        // delete Category
        $delete_category = $category->destroy($category->id);

        if (!$delete_category) {
            return response()->json($delete_category, 400);
        }

        return response()->json($delete_category, 200);
    }
}
