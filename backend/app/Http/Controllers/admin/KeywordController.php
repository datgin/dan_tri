<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Keyword;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class KeywordController extends Controller
{
    public function index()
    {
        $keywords = Keyword::paginate(10);
        // dd(vars: $keywords->all());
        return view('backend.keyword.index', compact('keywords'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255|string|unique:keywords,name',
        ]);

        Keyword::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name)
        ]);

        return back()->with('success', 'Thêm mới thành công');
    }
}