<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PageRequest;
use App\Page;
use Illuminate\Http\Request;
use View;
use Yajra\Datatables\Datatables;

class FooterPagesController extends Controller
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
        $footer = 1;
        if ($request->ajax()) {
            $pages = (new Page())->newQuery();

            return Datatables::of($pages)

                ->editColumn('name', '<b>{!! str_limit($name, 30) !!}</b>')

                ->editColumn('created_at', function ($pages) {
                    return $pages->created_at;
                })

                ->editColumn('parent', function ($pages) {
                    $parent = Page::where('id', $pages->parent)->first();
                    if ($parent) {
                        return $parent->name;
                    } else {
                        // if it is a parent category, return nothing
                        return '';
                    }
                })

                ->addcolumn('options', function ($pages) {
                    $parent = Page::where('id', $pages->parent)->first();
                    $show = '';
                    if (! empty($parent->slug)) {
                        $show = '<a href="'.route('page.footer.show', [$parent->slug, $pages->slug]).'" target="_blank" class="btn btn-sm btn-info"><i class="fa fa-eye"></i> </a> ';
                    }
                    $edit = route('footer-pages.edit', $pages->id);
                    $delete_id = $pages->id;

                    $delete_button = '<button data-id="'.$delete_id.'" type="button" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i></button> ';

                    return $show.
                    '<a href="'.$edit.'" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> </a> '.
                    $delete_button;
                })

                ->make(true);
        }

        return view('admin.pages.index', compact('pages', 'footer'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $footer = 1;
        return view('admin.pages.page_create', compact('footer'));
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
        $footer = 1;

        Page::create($request->all());

        flash('Page has been Created', 'success');

        return redirect()->route('pages.index', compact('footer'));
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
        $footer = 1;

        $page = Page::findOrFail($id);

        return view('admin.pages.page_edit', compact('page', 'footer'));
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
        $footer = 1;

        Page::findOrFail($id)->update($request->all());

        flash('Page has been updated', 'success');

        return back()->with('footer', $footer);
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
