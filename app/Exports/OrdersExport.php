<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
class OrdersExport implements FromCollection, WithHeadings
{
    protected $orders;

    public function __construct(array $orders)
    {
        $this->orders = $orders;
    }

    public function collection()
    {
        $data = collect($this->orders)->map(function ($order) {
            $products = collect($order['line_items'])->map(function ($item) {
                return $item['title'] . ' (x' . $item['quantity'] . ')';
            })->implode(', ');

            return [
                'ID Pedido' => $order['id'],
                'Cliente' => ($order['customer']['first_name'] ?? '') . ' ' . ($order['customer']['last_name'] ?? ''),
                'Fecha' => \Carbon\Carbon::parse($order['created_at'])->format('d/m/Y'),
                'Productos' => $products,
                'Estado Financiero' => $order['financial_status'],
            ];
        });

        return $data;
    }

    public function headings(): array
    {
        return [
            'ID Pedido',
            'Cliente',
            'Fecha',
            'Productos',
            'Estado Financiero',
        ];
    }
}
