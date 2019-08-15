<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PageRequest;
use App\Page;
use Illuminate\Http\Request;
use View;
use Yajra\Datatables\Datatables;

class PagesController extends Controller
{
    public function __construct()
    {
        View::share('menu', 'pages');
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
            $pages = (new Page())->where('footer', 0)->newQuery();

            return Datatables::of($pages)

                ->editColumn('name', '<b>{!! str_limit($name, 30) !!}</b>')

                ->editColumn('created_at', function ($pages) {
                    return $pages->created_at;
                })

                ->addcolumn('options', function ($pages) {
                    $show = route('page.show', $pages->slug);
                    $edit = route('pages.edit', $pages->id);
                    $delete_id = $pages->id;

                    $delete_button =
                        '<button data-id="'.$delete_id.'" type="button" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i></button> ';

                    return '<a href="'.$show.'" target="_blank" class="btn btn-sm btn-info"><i class="fa fa-eye"></i> </a> '.
                    '<a href="'.$edit.'" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> </a> '.
                    $delete_button;
                })

                ->make(true);
        }

        return view('admin.pages.index', compact('pages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.pages.page_create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(PageRequest $request)
    {
        //
        Page::create($request->all());

        flash('Page has been Created', 'success');

        return redirect()->route('pages.index');
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
        $page = Page::findOrFail($id);

        return view('admin.pages.page_edit', compact('page'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(PageRequest $request, $id)
    {
        //
        Page::findOrFail($id)->update($request->all());

        flash('Page has been Updated', 'success');

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
        Page::destroy($id);

        return response()->json('deleted', 200);
    }
}
