<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Catalogue;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use SebastianBergmann\CodeUnit\FunctionUnit;

class CatalogueController extends Controller
{
    public function index()
    {
        $catalogues = Catalogue::paginate(10);

        
        return view('backend.catalogue.index', compact('catalogues'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255|string|unique:catalogues,name',

        ]);

        Catalogue::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name)

        ]);

        // dd($request->all());

        return redirect()->route('admin.catalogues.index')->with('success', 'Thêm mới thành công');
    }

    public function edit($slug)
    {
        $catalogue = Catalogue::where('slug', $slug)->firstOrFail();
        return view('backend.catalogue.edit', compact('catalogue'));
    }
}
