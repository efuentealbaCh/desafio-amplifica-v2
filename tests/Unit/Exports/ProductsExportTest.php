<?php

use PHPUnit\Framework\TestCase;
use App\Exports\ProductsExport;
use Illuminate\Support\Collection;

class ProductsExportTest extends TestCase
{
    public function test_it_returns_correct_headings()
    {
        $export = new ProductsExport([]);
        $this->assertEquals(['Nombre', 'SKU', 'Precio', 'Estado'], $export->headings());
    }

    public function test_it_maps_products_to_correct_collection()
    {
        $products = [
            ['title' => 'Test Product', 'variants' => [['sku' => 'TEST-001', 'price' => '10.50']], 'status' => 'active'],
        ];

        $export = new ProductsExport($products);
        $collection = $export->collection();

        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertEquals('Test Product', $collection[0]['Nombre']);
        $this->assertEquals('TEST-001', $collection[0]['SKU']);
    }
}
