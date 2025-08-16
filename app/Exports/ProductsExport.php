<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductsExport implements FromCollection, WithHeadings
{
    protected $products;

    public function __construct(array $products)
    {
        $this->products = $products;
    }

    public function collection()
    {
        $data = collect($this->products)->map(function ($product) {
            return [
                'Nombre' => $product['title'],
                'SKU' => $product['variants'][0]['sku'] ?? 'N/A',
                'Precio' => $product['variants'][0]['price'] ?? 'N/A',
                'Estado' => $product['status'],
            ];
        });

        return $data;
    }

    public function headings(): array
    {
        return [
            'Nombre',
            'SKU',
            'Precio',
            'Estado',
        ];
    }
}
