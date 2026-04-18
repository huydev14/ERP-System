<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use DragonCode\Support\Facades\Helpers\Str;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;

class ProductController extends Controller
{
    public function index()
    {
        return view('products.index');
    }

    public function data(Request $request)
    {
        if ($request->ajax()) {
            $products = Product::with(['category', 'brand'])->select('products.*');

            if ($request->filled('product_id')) {
                $products->where('products.id', $request->product_id);
            }

            if ($request->filled('category_id')) {
                $products->where('products.category_id', $request->category_id);
            }

            if ($request->filled('brand_id')) {
                $products->where('products.brand_id', $request->brand_id);
            }

            if ($request->filled('status')) {
                $products->where('products.status', $request->status);
            }

            return DataTables::of($products)
                ->addColumn('category_name', function ($product) {
                    return $product->category?->name ?? '<span class="tw-text-gray-400 tw-italic tw-text-sm">---</span>';
                })
                ->addColumn('brand_name', function ($product) {
                    return $product->brand?->name ?? '<span class="tw-text-gray-400 tw-italic tw-text-sm">---</span>';
                })
                ->editColumn('status', function ($product) {
                    return match ($product->status) {
                        'published' => '<span class="tw-px-2 tw-py-1 tw-bg-green-100 tw-text-green-700 tw-text-xs tw-font-medium tw-rounded-full">' . __('product.status.published') . '</span>',
                        'archived' => '<span class="tw-px-2 tw-py-1 tw-bg-gray-100 tw-text-gray-600 tw-text-xs tw-font-medium tw-rounded-full">' . __('product.status.archived') . '</span>',
                        default => '<span class="tw-px-2 tw-py-1 tw-bg-yellow-100 tw-text-yellow-700 tw-text-xs tw-font-medium tw-rounded-full">' . __('product.status.draft') . '</span>',
                    };
                })
                ->editColumn('created_at', function ($product) {
                    return $product->created_at ? $product->created_at->format('d/m/Y') : '';
                })
                ->editColumn('updated_at', function ($product) {
                    return $product->updated_at ? $product->updated_at->format('d/m/Y') : '';
                })
                ->editColumn('action', function ($product) {
                    return view('products._products-action', compact('product'))->render();
                })
                ->rawColumns(['category_name', 'brand_name', 'status', 'action'])
                ->make(true);
        }
    }

    public function getFilterData(Request $request)
    {
        $status = collect([
            ['id' => 'draft', 'text' => __('product.status.draft')],
            ['id' => 'published', 'text' => __('product.status.published')],
            ['id' => 'archived', 'text' => __('product.status.archived')],
        ]);

        $products = Product::select('id', 'name as text')->orderBy('name')->get();
        $categories = Category::select('id', 'name as text')->orderBy('name')->get();
        $brands = Brand::select('id', 'name as text')->orderBy('name')->get();

        return response()->json([
            'status' => $status,
            'products' => $products,
            'categories' => $categories,
            'brands' => $brands,
        ]);
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();

        return view('products.create', compact('categories', 'brands'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:2|max:255|unique:products,name',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'status' => 'required|in:draft,published,archived',
            'metadata' => 'nullable|json',
        ], [
            'name.required' => __('product.validation.name_required'),
            'name.unique' => __('product.validation.name_unique'),
            'category_id.required' => __('product.validation.category_required'),
            'status.required' => __('product.validation.status_required'),
            'metadata.json' => __('product.validation.metadata_json'),
        ]);

        try {
            Product::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'description' => $request->description,
                'category_id' => $request->category_id,
                'brand_id' => $request->brand_id,
                'status' => $request->status,
                'metadata' => $request->metadata ? json_decode($request->metadata, true) : null,
            ]);

            if ($request->ajax()) {
                session()->flash('success', __('product.messages.create_success'));

                return response()->json([
                    'success' => true,
                    'msg' => __('product.messages.create_success'),
                ], 200);
            }

            return redirect()->route('products.index')->with('success', __('product.messages.create_success'));
        } catch (Exception $e) {
            Log::error('Create product failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'status' => 'error',
                'msg' => __('product.messages.system_error'),
            ], 500);
        }
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();

        return view('products.edit', compact('product', 'categories', 'brands'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|min:2|max:255|unique:products,name,' . $product->id,
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'status' => 'required|in:draft,published,archived',
            'metadata' => 'nullable|json',
        ], [
            'name.required' => __('product.validation.name_required'),
            'name.unique' => __('product.validation.name_unique'),
            'category_id.required' => __('product.validation.category_required'),
            'status.required' => __('product.validation.status_required'),
            'metadata.json' => __('product.validation.metadata_json'),
        ]);

        try {
            $product->update([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'description' => $request->description,
                'category_id' => $request->category_id,
                'brand_id' => $request->brand_id,
                'status' => $request->status,
                'metadata' => $request->metadata ? json_decode($request->metadata, true) : null,
            ]);

            return response()->json([
                'success' => true,
                'msg' => __('product.messages.update_success'),
            ], 200);
        } catch (Exception $e) {
            Log::error('Update product failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'msg' => __('product.messages.system_error'),
            ], 500);
        }
    }

    public function destroy(Product $product)
    {
        try {
            $product->delete();

            return response()->json([
                'success' => true,
                'status' => 200,
                'msg' => __('product.messages.delete_success'),
            ]);
        } catch (Exception $e) {
            Log::error('Delete product failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'msg' => __('product.messages.system_error'),
            ], 500);
        }
    }

    public function restore($id)
    {
        try {
            $product = Product::withTrashed()->findOrFail($id);
            $product->restore();

            return response()->json([
                'success' => true,
                'msg' => __('product.messages.restore_success'),
            ]);
        } catch (Exception $e) {
            Log::error('Restore product failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'msg' => __('product.messages.restore_error'),
            ], 500);
        }
    }
}
