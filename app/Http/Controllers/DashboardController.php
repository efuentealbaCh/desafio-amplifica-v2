<?php

namespace App\Http\Controllers;

use App\Services\ShopifyService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    protected $shopifyService;

    public function __construct(ShopifyService $shopifyService)
    {
        $this->shopifyService = $shopifyService;
    }

    public function index(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $orders = $this->shopifyService->fetchAllOrders($startDate, $endDate);
        $monthlySalesData = $this->getMonthlySalesData($orders);

        $topSellingProductsData = $this->getTopSellingProductsData($orders);

        $products = $this->shopifyService->getProducts()['products'];
        $availableProductsData = $this->getAvailableProductsData($products);

        return view('dashboard', [
            'monthlySalesData' => $monthlySalesData,
            'topSellingProductsData' => $topSellingProductsData,
            'availableProductsData' => $availableProductsData,
        ]);
    }

    private function getMonthlySalesData(array $orders)
    {
        $salesByMonth = [];
        foreach ($orders as $order) {
            $month = Carbon::parse($order['created_at'])->format('Y-m');
            $quantity = 0;
            foreach ($order['line_items'] as $item) {
                $quantity += $item['quantity'];
            }
            if (!isset($salesByMonth[$month])) {
                $salesByMonth[$month] = 0;
            }
            $salesByMonth[$month] += $quantity;
        }

        return [
            'labels' => array_keys($salesByMonth),
            'data' => array_values($salesByMonth),
        ];
    }

    private function getTopSellingProductsData(array $orders)
    {
        $productSales = [];
        foreach ($orders as $order) {
            foreach ($order['line_items'] as $item) {
                $title = $item['title'];
                if (!isset($productSales[$title])) {
                    $productSales[$title] = 0;
                }
                $productSales[$title] += $item['quantity'];
            }
        }

        arsort($productSales);

        $topSellers = array_slice($productSales, 0, 10, true);

        return [
            'labels' => array_keys($topSellers),
            'data' => array_values($topSellers),
        ];
    }

    private function getAvailableProductsData(array $products)
    {
        $productAvailability = [];
        foreach ($products as $product) {
            $productAvailability[$product['title']] = $product['variants'][0]['inventory_quantity'];
        }

        return [
            'labels' => array_keys($productAvailability),
            'data' => array_values($productAvailability),
        ];
    }
}
