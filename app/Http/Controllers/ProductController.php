<?php

namespace App\Http\Controllers;

use App\Services\ShopifyService;
use Illuminate\Http\Request;
use App\Exports\ProductsExport;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    protected $shopifyService;

    public function __construct(ShopifyService $shopifyService)
    {
        $this->shopifyService = $shopifyService;
    }

    public function index()
    {
        $products = $this->shopifyService->getProducts();

        if ($products === null) {
            return redirect()->back();
        }

        $products = $products['products'] ?? [];

        return view('products.index', [
            'products' => $products,
        ]);
    }

    public function export()
    {
        $products = $this->shopifyService->getProducts();
        $productsArray = $products['products'];
        return Excel::download(new ProductsExport($productsArray), 'productos_shopify.xlsx');
    }

    public function exportCsv()
    {
        $products = $this->shopifyService->getProducts();
        $productsArray = $products['products'];
        return Excel::download(new ProductsExport($productsArray), 'productos_shopify.csv', \Maatwebsite\Excel\Excel::CSV);
    }
}
