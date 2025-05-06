<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\OriginRequest;
use App\Models\SgoOrigin;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class OriginController extends Controller
{
    public function index(Request $request)
    {
        $title = "Xuất xứ";
        if ($request->ajax()) {
            $data = SgoOrigin::select('id', 'name', 'slug', 'description');

            return DataTables::of($data)
                ->addColumn('description', function ($row) {
                    return $row->description; // Trả về nội dung mô tả
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('admin.origin.edit', $row->id);
                    $deleteUrl = route('admin.origin.delete', $row->id);

                    // Nút Sửa và Xóa
                    $actions = '<div style="display: flex; gap: 10px;">
                                <a href="' . $editUrl . '" class="btn btn-warning btn-sm edit">
                                    <i class="fas fa-edit" title="Sửa"></i> Sửa
                                </a>
                                <button type="button" class="btn btn-danger btn-sm delete"
                                    onclick="confirmDelete(' . $row->id . ')">
                                    <i class="fas fa-trash" title="Xóa"></i> Xóa
                                </button>
                                <form id="delete-form-' . $row->id . '" action="' . $deleteUrl . '" method="POST" style="display: none;">
                                    ' . csrf_field() . '
                                    ' . method_field('DELETE') . '
                                </form>
                            </div>';
                    return $actions;
                })
                ->rawColumns(['action', 'description']) // Đảm bảo HTML không bị escape
                ->make(true);
        }

        $page = 'Xuất xứ';
        return view('backend.origin.index', compact('title', 'page'));
    }

    public function edit($id)
    {
        $origin = SgoOrigin::findOrFail($id);
        return response()->json($origin);
    }
    public function store(Request $request)
    {
        // Validate dữ liệu
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        // Tạo mới bản ghi origin
        $origin = new SgoOrigin();
        $origin->name = $validated['name'];
        $origin->slug = Str::slug($validated['name']);
        $origin->description = $validated['description'];
        $origin->save();

        // Trả về phản hồi JSON
        return response()->json([
            'success' => true,
            'message' => 'Thêm xuất xứ thành công!',
            'data' => $origin
        ]);
    }
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:sgo_origin,name,' . $id,
            'description' => 'required|string',
        ]);

        // Tìm origin cần sửa
        $origin = SgoOrigin::findOrFail($id);
        $origin->name = $validated['name'];
        $origin->slug = Str::slug($validated['name']);
        $origin->description = $validated['description'];
        $origin->save();

        // Trả về phản hồi JSON
        return response()->json([
            'success' => true,
            'message' => 'Cập nhật xuất xứ thành công!',
            'data' => $origin
        ]);
    }

    public function delete($id)
    {
        $origin = SgoOrigin::findOrFail($id);

        $origin->delete();
        return response()->json([
            'success' => true,
            'message' => 'Xuất xứ đã được xóa thành công!'
        ]);
    }
}
