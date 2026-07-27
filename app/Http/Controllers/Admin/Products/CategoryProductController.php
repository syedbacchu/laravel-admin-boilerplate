<?php

namespace App\Http\Controllers\Admin\Products;

use App\Http\Controllers\Controller;
use App\Http\Services\CategoryProduct\CategoryProductServiceInterface;
use App\Http\Services\Response\ResponseService;
use App\Support\DataListManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryProductController extends Controller
{
    protected CategoryProductServiceInterface $categoryProduct;

    public function __construct(CategoryProductServiceInterface $categoryProduct)
    {
        $this->categoryProduct = $categoryProduct;
    }

    /*
    |--------------------------------------------------------------------------
    | LIST (DataTable)
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $data['pageTitle'] = __('Category Product List');

        if ($request->ajax()) {
            return DataListManager::dataTableHandle(
                request: $request,
                dataProvider: function ($request) {
                    return $this->categoryProduct->getDataTableData($request)['data']['data'];
                },
                columns: [
                    'image' => fn ($item) => $item->image
                        ? '<img src="' . $item->image . '" class="h-12 w-12 rounded object-cover">'
                        : '<span class="text-gray-400">-</span>',

                    'name' => fn ($item) => $item->name ?? '-',
                    'slug' => fn ($item) => $item->slug ?? '-',
                    'parent' => fn ($item) => optional($item->parent)->name ?? '-',
                    'product_count' => fn ($item) => $item->product_count ?? 0,

                    'status_toggle' => fn ($item) => toggle_column(
                        route('category-product.publish'),
                        $item->id,
                        $item->status === 1
                    ),

                    'actions' => function ($item) {
                        $buttons = [
                            '<a href="' . route('category-product.show', $item->id) . '"
                               class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i> Details
                             </a>',
                        ];

                        return action_buttons($buttons);
                    },
                ],
                rawColumns: ['image', 'status_toggle', 'actions']
            );
        }

        return ResponseService::send([
            'data' => $data,
        ], null, \App\Http\Services\Response\Viewed::get('category_product', 'list'));
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW (Category Details + Products)
    |--------------------------------------------------------------------------
    */
    public function show(string $id)
    {
        $response = $this->categoryProduct->getCategoryDetails($id);

        if ($response['success'] === false) {
            return ResponseService::send();
        }

        $category = $response['data']['category'];
        $products = $response['data']['products'];

        $data['pageTitle'] = __('Category Details: ') . $category->name;
        $data['category'] = $category;
        $data['products'] = $products;

        return ResponseService::send([
            'data' => $data,
        ], null, \App\Http\Services\Response\Viewed::get('category_product', 'show'));
    }

    /*
    |--------------------------------------------------------------------------
    | STATUS TOGGLE (AJAX)
    |--------------------------------------------------------------------------
    */
    public function categoryStatus(Request $request): JsonResponse
    {
        try {
            $response = $this->categoryProduct->publish($request->id, $request->status);
            return response()->json($response);
        } catch (\Exception $e) {
            logStore('categoryProductStatus', $e->getMessage());
            return response()->json(['success' => false, 'message' => somethingWrong()]);
        }
    }
}