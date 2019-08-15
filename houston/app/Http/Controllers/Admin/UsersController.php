<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UsersRequest;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

class UsersController extends Controller
{
    public function __construct()
    {
        view()->share('menu', 'users');
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
            $users = (new User())->newQuery();

            return Datatables::of($users)

                ->addColumn('username', function ($users) {
                    if ($users->isBlocked()) {
                        return '<span style="text-decoration: line-through"><i class="fa fa-ban"></i> '.$users->username.'</span>';
                    } else {
                        return $users->username;
                    }
                })

                ->addColumn('uploads', function ($users) {
                    return $users->media->count();
                })

                ->addColumn('points', function ($users) {
                    return $users->pointsSum();
                })

                ->editColumn('created_at', function ($users) {
                    return $users->created_at->format('Y-m-d');
                })

                ->addColumn('options', function ($users) {
                    $show = route('user.profile.index', $users->username);
                    $edit = route('users.edit', $users->id);
                    $delete_id = $users->id;
                    $delete_name = $users->name;
                    $delete_posts = $users->media->count();
                    if ($users->isBlocked()) {
                        $button = '<button data-id="'.$delete_id.'" data-media="'.$delete_posts.'" data-name="'.$delete_name.'" id="test" type="button"
                        class="btn btn-success btn-sm">
                       <i class="fa fa-check"></i></button>';
                    } else {
                        $button = '<button data-id="'.$delete_id.'" data-media="'.$delete_posts.'" data-name="'.$delete_name.'" id="test" type="button" class="btn btn-danger btn-sm"><i class="fa fa-ban"></i></button>';
                    }

                    if ($users->type == 'admin') {
                        $button = '<button disabled data-id="'.$delete_id.'" data-media="'.$delete_posts.'" data-name="'.$delete_name.'" id="test" type="button" class="btn btn-danger btn-sm"><i class="fa fa-ban"></i></button>';
                    }

                    return '<a href="'.$show.'" class="btn btn-sm btn-info"><i class="fa fa-eye"></i> </a> '.
                    '<a href="'.$edit.'" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> </a> '.
                    $button;
                })

                ->make(true);
        }

        return view('admin.users.index');
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
        $user = User::findOrFail($id);

        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UsersRequest $request, $id)
    {
        //
        if ($request->input('password') == '') {
            $request->except('password');
        } else {
            $request->merge(['password' => bcrypt($request->input('password'))]);
        }

        User::findOrFail($id)->update($request->all());

        flash('User Has Been Updated!', 'success');

        return back();
    }

    public function block(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($user->id == $request->user()->id) {
            return response()->json(400);
        }

        if ($user->isBlocked()) {
            $user->update([
                'blocked_on' => null,
            ]);

            return response()->json('unBanned');
        } else {
            $user->update([
                'blocked_on' => Carbon::now(),
            ]);

            return response()->json('Banned');
        }
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
    }
}
