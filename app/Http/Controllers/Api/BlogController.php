<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\DataTables\BlogDataTable;
use App\Facades\UtilityFacades;
use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Http\Helpers\Api\Helpers as ApiHelpers;

class BlogController extends Controller
{
    public function index(BlogDataTable $dataTable)
    {
        if (\Auth::user()->can('manage-blog')) {
            return $dataTable->render('blog.index');
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }

    public function create()
    {
        if (\Auth::user()->can('create-blog')) {
            $categories = BlogCategory::where('status', 1)->pluck('name', 'id');
            return view('blog.create', compact('categories'));
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }

    public function store(Request $request)
    {
        if (\Auth::user()->can('create-blog')) {
            request()->validate([
                'title'             => 'required|max:191|unique:blogs',
                'images'            => 'required|image|mimes:jpeg,jpg,png|max:2048',
                'description'       => 'required',
                'short_description' => 'required',
                'category_id'       => 'required',
            ]);
            if ($request->hasFile('images')) {
                request()->validate([
                    'images' => 'required|image|mimes:jpeg,jpg,png|max:2048',
                ]);
                $path = $request->file('images')->store('blogs');
            }
              Blog::create([
                'title'             => $request->title,
                'description'       => $request->description,
                'category_id'       => $request->category_id,
                'images'            => $path,
                'short_description' => $request->short_description,
                'created_by'        => \Auth::user()->id,
            ]);
            return redirect()->route('blogs.index')->with('success', __('Blog created successfully.'));
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }

   public function edit(Blog $blog)
    {
        if (\Auth::user()->can('edit-blog')) {
            $categories = BlogCategory::where('status', 1)->pluck('name', 'id');
            return view('blog.edit', compact('blog', 'categories'));
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }


    public function update(Request $request, $id)
    {
        if (\Auth::user()->can('edit-blog')) {
            request()->validate([
                'title'             => 'required|max:191',
                'description'       => 'required',
                'short_description' => 'required',
                'category_id'       => 'required',
            ]);
            $blog = Blog::find($id);
            if ($request->hasFile('images')) {
                request()->validate([
                    'images' => 'required|image|mimes:jpeg,jpg,png|max:2048',
                ]);
                $path         = $request->file('images')->store('blogs');
                $blog->images = $path;
            }
            $blog->title                = $request->title;
            $blog->description          = $request->description;
            $blog->category_id          = $request->category_id;
            $blog->short_description    = $request->short_description;
            $blog->created_by           = \Auth::user()->id;
            $blog->save();
            return redirect()->route('blogs.index')->with('success', __('blogs updated successfully.'));
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }

    public function destroy($id)
    {
        if (\Auth::user()->can('delete-blog')) {
            $post = Blog::find($id);
            $post->delete();
            return redirect()->route('blogs.index')->with('success', __('Posts deleted successfully.'));
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }

    public function viewBlog($slug)
    {
        $lang = UtilityFacades::getActiveLanguage();
        \App::setLocale($lang);
        $blog       =  Blog::where('slug', $slug)->first();
        if (!$blog) {
            abort(404);
        }

        $data = ['news' => $blog];
        $message =  ['success'=>[__('Detalle Noticia')]];
        return ApiHelpers::success($data,$message);

        /*$allBlogs  =  Blog::all();
        return view('blog.view-blog', compact('blog', 'allBlogs', 'slug', 'lang'));*/
    }

    public function obtenerNoticias(Request $request)
    {
        $lang = UtilityFacades::getActiveLanguage();
        \App::setLocale($lang);

        $allBlogs = Blog::all();
        
        $recentBlogs    = Blog::latest()->take(9)->get();
        $lastBlog       = Blog::latest()->first();
        $categories     = BlogCategory::all();

        $data = ['news' => $allBlogs];
        $message =  ['success'=>[__('Noticias')]];
        return ApiHelpers::success($data,$message);

       // return view('blog.view-all-blogs', compact('allBlogs', 'recentBlogs', 'lastBlog', 'categories', 'lang'));
    }

    public function obtenerCategorias(Request $request)
    {
        $categories     = BlogCategory::all();

        $data = ['categorias' => $categories];
        $message =  ['success'=>[__('Categorías')]];
        return ApiHelpers::success($data,$message);

       // return view('blog.view-all-blogs', compact('allBlogs', 'recentBlogs', 'lastBlog', 'categories', 'lang'));
    }
}
