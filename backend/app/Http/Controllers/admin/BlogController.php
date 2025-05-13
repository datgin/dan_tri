<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Catalogue;
use App\Models\Keyword;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class BlogController extends Controller
{
    //
    public function index(Request $request)
    {
        $blogs = Blog::all();
        return view('backend.blogs.index', compact('blogs'));
    }


    public function create()
    {
        $blog = new Blog();

        $blog->tag_ids = [];
        $blog->keyword_ids = [];
        $catalogues = Catalogue::all();
        $tags = Tag::all();
        $keywords = Keyword::all();

        return view('backend.blogs.form', compact('catalogues', 'tags', 'keywords', 'blog'));
    }


    public function edit($id)
    {
        $blog = Blog::with('blogTags', 'keywords')->findOrFail($id);
        // dd($blog->blogTags, $blog->keywords);
        $catalogues = Catalogue::all();
        $tags = Tag::all();
        $keywords = Keyword::all();

        return view('backend.blogs.form', compact('blog', 'catalogues', 'tags', 'keywords'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blogs,slug',
            'content' => 'required|string',
            'catalogue_id' => 'required|integer|exists:catalogues,id',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'short_description' => 'nullable|string|max:500',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:255',
            'tags' => 'nullable',
            'tags.*' => 'string',
            'keywords' => 'nullable',
            'keywords.*' => 'string',
            'status' => 'nullable|boolean',
            'posted_at' => 'nullable',
            'remove_at' => 'nullable',
        ]);


        if (!empty($request->tags)) {
            // Giải mã chuỗi JSON thành mảng
            $tagsArray = json_decode($request->tags, true);
            $keywordsArray = json_decode($request->keywords, true);

            $tags = array_map(fn($tag) => $tag['value'], $tagsArray);
            $keywords = array_map(fn($keyword) => $keyword['value'], $keywordsArray);

            // dd(vars: $tags);

            $arrayTags = [];
            $arrayKeywords = [];

            foreach ($tags as $item) {
                $tag = Tag::query()->updateOrCreate(['name' => $item], ['slug' => Str::slug($item)]);
                $arrayTags[] = $tag->id;

            }

            foreach ($keywords as $item) {
                $keyword = Keyword::query()->updateOrCreate(['name' => $item], ['slug' => Str::slug($item)]);
                $arrayKeywords[] = $keyword->id;
            }

        }

        try {
            // dd(vars: $request->all());

            // Tạo danh mục mới
            $blog = Blog::create([
                'title' => $request->input('title'),
                'slug' => Str::slug($request->input('title')),
                'content' => $request->input('content'),
                'image' => saveImage($request, 'image', 'new_images'),
                'catalogue_id' => $request->input('catalogue_id'),
                'short_description' => $request->input('short_description'),
                'seo_title' => $request->input('seo_title'),
                'seo_description' => $request->input('seo_description'),
                'posted_at' => now(),
                'view_count' => '0',
                'status' => $request->input('status', 0),
            ]);

            // Gán các tags và keywords cho bài viết
            $blog->tags()->sync($arrayTags);
            $blog->keywords()->sync($arrayKeywords);


            if ($request->hasFile('image')) {
                $credentials['image'] = saveImage($request, 'image', 'new_images');
            }


            // Trả về thông báo thành công
            return redirect()->route('admin.blogs.index')->with('success', 'Bài viết đã được thêm thành công');
        } catch (\Exception $e) {
            // Nếu có lỗi, bắt và hiển thị thông báo lỗi
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {

        try {

            $blog = Blog::findOrFail($id);
            $slug = $request->input('slug') ?? Str::slug($request->input('title'));

            $request->merge(['slug' => $slug]);

            $request->validate([
                'title' => 'required|string|max:255',
                'slug' => 'nullable|string|max:255|unique:blogs,slug,' . $id,
                'content' => 'required|string',
                'catalogue_id' => 'required|integer|exists:catalogues,id',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
                'short_description' => 'nullable|string|max:500',
                'seo_title' => 'nullable|string|max:255',
                'seo_description' => 'nullable|string|max:255',
                'tags' => 'nullable',
                'tags.*' => 'string',
                'keywords' => 'nullable',
                'keywords.*' => 'string',
                'status' => 'nullable|boolean',
                'posted_at' => 'nullable',
                'remove_at' => 'nullable',
            ]);

            if (!empty($request->tags)) {
                // Giải mã chuỗi JSON thành mảng
                $tagsArray = json_decode($request->tags, true);
                $keywordsArray = json_decode($request->keywords, true);

                $tags = array_map(fn($tag) => $tag['value'], $tagsArray);
                $keywords = array_map(fn($keyword) => $keyword['value'], $keywordsArray);

                $arrayTags = [];
                $arrayKeywords = [];

                foreach ($tags as $item) {
                    $tag = Tag::query()->updateOrCreate(['name' => $item], ['slug' => Str::slug($item)]);
                    $arrayTags[] = $tag->id;
                }

                foreach ($keywords as $item) {
                    $keyword = Keyword::query()->updateOrCreate(['name' => $item], ['slug' => Str::slug($item)]);
                    $arrayKeywords[] = $keyword->id;
                }

                // Cập nhật tags và keywords
                $blog->tags()->sync($arrayTags);
                $blog->keywords()->sync($arrayKeywords);
            }

            $credentials = $request->only([
                'title',
                'slug',
                'content',
                'catalogue_id',
                'short_description',
                'seo_title',
                'seo_description',
                'status',
                'posted_at',
                'remove_at'
            ]);


            if ($request->hasFile('image')) {
                // Xóa ảnh cũ nếu tồn tại
                if ($blog->image && file_exists(public_path($blog->image))) {
                    unlink(public_path($blog->image));
                }
                $credentials['image'] = saveImage($request, 'image', 'new_images');
            }

            $blog->update($credentials);

            // Trả về thông báo thành công
            return redirect()->route('admin.blogs.index')->with('success', 'Bài viết đã được sửa thành công');
        } catch (\Exception $e) {
            // Nếu có lỗi, bắt và hiển thị thông báo lỗi
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $blog = Blog::findOrFail($id);

            // Xóa ảnh nếu tồn tại
            if ($blog->image && file_exists(public_path('storage/' . $blog->image))) {
                unlink(public_path('storage/' . $blog->image));
            }

            $blog->delete();

            return response()->json([
                'success' => true,
                'message' => 'Xóa bài viết thành công!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }
}
