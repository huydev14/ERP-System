<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Carbon\Carbon;
use DragonCode\Support\Facades\Helpers\Str;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;

class BrandController extends Controller
{
    public function index()
    {
        return view('brands.index');
    }

    public function data(Request $request)
    {
        if ($request->ajax()) {
            $brands = Brand::query();

            return DataTables::of($brands)
                ->editColumn('logo', function ($brand) {
                    if ($brand->logo) {
                        $url = asset('storage/' . $brand->logo);
                        return '<img src="' . $url . '" alt="' . $brand->name . '" class="tw-h-8 tw-w-auto tw-object-contain tw-rounded">';
                    }
                    return '<span class="tw-text-gray-400 tw-italic tw-text-sm">Không có ảnh</span>';
                })
                ->editColumn('is_active', function ($brand) {
                    if ($brand->is_active) {
                        return '<span class="tw-px-2 tw-py-1 tw-bg-green-100 tw-text-green-700 tw-text-xs tw-font-medium tw-rounded-full">Đang hoạt động</span>';
                    }
                    return '<span class="tw-px-2 tw-py-1 tw-bg-gray-100 tw-text-gray-600 tw-text-xs tw-font-medium tw-rounded-full">Đã ẩn</span>';
                })

                ->editColumn('created_at', function ($brand) {
                    return $brand->created_at ? $brand->created_at->format('d/m/Y') : '';
                })
                ->editColumn('updated_at', function ($brand) {
                    return $brand->updated_at ? $brand->updated_at->format('d/m/Y') : '';
                })
                ->editColumn('action', function ($brand) {
                    return view('brands._brands-action', compact('brand'))->render();
                })
                ->rawColumns(['is_active', 'logo', 'action'])
                ->make(true);
        }
    }

    public function getFilterData(Request $request)
    {
        $isActive = collect([
            ['id' => 1, 'text' => 'Đang hoạt động'],
            ['id' => 0, 'text' => 'Ngừng hoạt động'],
        ]);
        $brandNames = Brand::select('id', 'name as text')->orderBy('name')->get();

        return response()->json([
            'isActive' => $isActive,
            'brandName' => $brandNames,
        ]);
    }

    public function create()
    {
        return view('brands.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|min:2|max:255|unique:brands,name',
            'website' => 'nullable|url|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'name.required' => 'Vui lòng nhập tên thương hiệu.',
            'name.unique' => 'Tên thương hiệu này đã tồn tại trên hệ thống.',
            'logo.max' => 'Kích thước ảnh logo không được vượt quá 2MB.',
        ]);

        $logoPath = null;

        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('brands', 'public');
        }
        try {
            $brand = Brand::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'website' => $request->website,
                'logo' => $logoPath,
                'is_active' => $request->has('is_active'),
            ]);

            return response()->json(['success' => true, 'msg' => 'Thêm thương hiệu thành công!'], 200);
        } catch (Exception $e) {

            Log::error('Create brand failed', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json([
                'status' => 'error',
                'msg' => 'Lỗi hệ thống'
            ], 500);
        }
    }
    public function edit()
    {
        return view('brands.edit');
    }
}
