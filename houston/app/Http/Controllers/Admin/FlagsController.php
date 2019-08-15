<?php

namespace App\Http\Controllers\Admin;

use App\Flag;
use App\Http\Controllers\Controller;
use App\Media;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

class FlagsController extends Controller
{
    public function __construct()
    {
        view()->share('menu', 'flags');
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
            $flags = Flag::all();

            return Datatables::of($flags)

                ->editColumn('user', function ($flags) {
                    return $flags->user_id == 0 ? 'guest' : $flags->user->username;
                })

                ->editColumn('reason', function ($flags) {
                    return $flags->reason;
                })

                ->editColumn('created_at', function ($flags) {
                    return $flags->created_at->format('Y-m-d');
                })

                ->addColumn('options', function ($flags) {
                    if ($flags->type == 'media') {
                        $show = route('media.show', Media::find($flags->flagged_id)->slug);
                        $edit = route('medias.edit', $flags->flagged_id);
                    }

                    if ($flags->type == 'comment') {
                        $show = route('media.show', Media::find($flags->flagged_id)->slug).'#comment-'.$flags->flagged_id;
                        $edit = route('comments.edit', $flags->flagged_id);
                    }

                    $delete_id = $flags->id;
                    $delete_name = $flags->type;
                    $delete_posts = '';

                    return '<a href="'.$show.'" class="btn btn-sm btn-info"><i class="fa fa-eye"></i> </a> '.
                    '<a href="'.$edit.'" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> </a> '.
                    '<button data-id="'.$delete_id.'" data-media="'.$delete_posts.'" data-name="'.$delete_name.'" id="test" type="button" class="btn btn-danger btn-sm">
                       <i class="fa fa-trash-o"></i></button>';
                })

                ->make(true);
        }

        return view('admin.flags.index');
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
        Flag::destroy($id);
    }
}
