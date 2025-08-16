<?php

namespace App\Http\Controllers;

use App\Services\ShopifyService;
use Illuminate\Http\Request;
use App\Exports\OrdersExport;
use Maatwebsite\Excel\Facades\Excel;

class OrderController extends Controller
{
    protected $shopifyService;

    public function __construct(ShopifyService $shopifyService)
    {
        $this->shopifyService = $shopifyService;
    }

    public function index()
    {
        $orders = $this->shopifyService->getOrders();
        if ($orders === null) {
            return redirect()->back();
        }

        return view('orders.index', [
            'orders' => $orders['orders'],
        ]);
    }

    public function exportExcel()
    {
        $orders = $this->shopifyService->getOrders();
        return Excel::download(new OrdersExport($orders['orders']), 'pedidos_shopify.xlsx');
    }

    public function exportCsv()
    {
        $orders = $this->shopifyService->getOrders();
        return Excel::download(new OrdersExport($orders['orders']), 'pedidos_shopify.csv', \Maatwebsite\Excel\Excel::CSV);
    }
}
