<?php

use PHPUnit\Framework\TestCase;
use App\Services\ShopifyService;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Log;


class ShopifyServiceTest extends TestCase
{
    public function test_get_products_returns_data_on_success()
    {
        $mock = new MockHandler([
            new Response(200, [], json_encode(['products' => [['id' => 1]]])),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        $service = new ShopifyService($client);

        $response = $service->getProducts();

        $this->assertIsArray($response);
        $this->assertArrayHasKey('products', $response);
    }
}
