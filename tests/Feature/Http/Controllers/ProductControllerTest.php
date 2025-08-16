<?php

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\ShopifyService;
use App\Models\User;
use Illuminate\Support\Facades\Session;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_displays_products_from_shopify()
    {
        $user = User::factory()->create();
        $mockProducts = ['products' => [['id' => 1, 'title' => 'Test Product']]];

        $this->mock(ShopifyService::class, function ($mock) use ($mockProducts) {
            $mock->shouldReceive('getProducts')->andReturn($mockProducts);
        });

        $response = $this->actingAs($user)->get('/products');

        $response->assertStatus(200);
        $response->assertViewHas('products');
        $response->assertSee('Test Product');
    }

    public function test_index_redirects_on_service_error()
    {
        $user = User::factory()->create();

        $this->mock(ShopifyService::class, function ($mock) {
            $mock->shouldReceive('getProducts')->andReturn(null);
        });

        Session::flash('error', 'Authentication error');

        $response = $this->actingAs($user)->get('/products');

        $response->assertStatus(302);
        $response->assertRedirect();
    }
}
