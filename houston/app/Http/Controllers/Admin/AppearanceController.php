<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Http\Controllers\Controller;
use App\Page;
use App\Setting;
use Cache;
use Illuminate\Http\Request;

class AppearanceController extends Controller
{
    public function __construct()
    {
        view()->share('menu', 'appearance');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getMenu()
    {
        //
        $pages = Page::where('footer', 0)->orderBy('order', 'asc')->get();
        $parents = Page::where('footer', 1)->where('parent', 0)->orderBy('order', 'asc')->get();
        $footer_pages = Page::where('footer', 1)->where('parent', '>', 0)->orderBy('order', 'asc')->get();
        $categories = Category::orderBy('order', 'asc')->get();
        $forum_categories = \DevDojo\Chatter\Models\Category::orderBy('order', 'asc')->get();

        return view('admin.appearance.menu', compact('pages', 'parents', 'footer_pages', 'categories', 'forum_categories'));
    }

    public function getThemes()
    {
        //
        $pages = Page::orderBy('order', 'asc')->get();
        $categories = Category::orderBy('order', 'asc')->get();

        return view('admin.appearance.themes', compact('pages', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function putSort($type, Request $request)
    {
        //
        if ($request->has('id')) {
            $i = 0;
            foreach ($request->get('id') as $id) {
                $i++;

                if ($type === 'categories') {
                    $item = Category::find($id);
                } elseif ($type === 'pages' || $type === 'footer-pages') {
                    $item = Page::find($id);
                } elseif ($type === 'forum_categories') {
                    $item = \DevDojo\Chatter\Models\Category::find($id);
                }

                $item->order = $i;
                $item->save();
            }

            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    public function updateTheme(Request $request)
    {
        Setting::where('name', 'appearance')->update([
            'attributes' => json_encode(['theme'=> $request->input('theme')]),
        ]);

        Cache::forget('attributes_appearance');

        flash('Your theme has been updated!', 'success');

        return back();
    }
}
