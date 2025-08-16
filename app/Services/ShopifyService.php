<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\ClientException;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ShopifyService
{
    protected $client;

    public function __construct(Client $client = null)
    {
        $this->client = $client ?? new Client([
            'base_uri' => 'https://' . env('SHOPIFY_STORE_DOMAIN') . '/admin/api/2023-10/',
            'headers' => [
                'X-Shopify-Access-Token' => env('SHOPIFY_API_SECRET'),
                'Accept' => 'application/json',
            ],
            'timeout' => 10,
        ]);
    }

    public function getProducts()
    {
        return $this->makeRequest('GET', 'products.json');
    }

    public function getOrders()
    {
        return $this->makeRequest('GET', 'orders.json');
    }

    public function fetchAllOrders($startDate = null, $endDate = null)
    {
        $allOrders = [];
        $pageInfo = null;

        do {
            $queryParams = [
                'limit' => 250,
                'status' => 'any'
            ];

            if ($pageInfo) {
                $queryParams['page_info'] = $pageInfo;
            }

            if ($startDate) {
                $queryParams['created_at_min'] = Carbon::parse($startDate)->startOfDay()->toIso8601String();
            }

            if ($endDate) {
                $queryParams['created_at_max'] = Carbon::parse($endDate)->endOfDay()->toIso8601String();
            }

            $response = $this->makeRequest('GET', 'orders.json', ['query' => $queryParams], true); // Se pasa true para obtener el objeto de respuesta completo

            if ($response === null || !isset($response['orders'])) {
                return [];
            }

            $allOrders = array_merge($allOrders, $response['orders']);

            $pageInfo = $this->getNextPageInfo($response['headers']['Link'][0] ?? null);
        } while ($pageInfo);

        return $allOrders;
    }

    protected function makeRequest($method, $uri, $options = [], $returnFullResponse = false)
    {
        try {
            $response = $this->client->request($method, $uri, $options);

            $data = json_decode($response->getBody()->getContents(), true);

            if ($returnFullResponse) {
                $data['headers'] = $response->getHeaders();
                return $data;
            }

            return $data;
        } catch (ClientException $e) {
            Log::error('Shopify API Client Error: ' . $e->getMessage() . ' for ' . $uri);
            session()->flash('error', 'Error de autenticación. Por favor, revisa tus credenciales.');
            return null;
        } catch (GuzzleException $e) {
            Log::error('Shopify API Guzzle Error: ' . $e->getMessage() . ' for ' . $uri);
            session()->flash('error', 'Ocurrió un error al conectar con la API de Shopify.');
            return null;
        }
    }

    protected function getNextPageInfo($linkHeader)
    {
        if (empty($linkHeader)) {
            return null;
        }

        $links = explode(',', $linkHeader);
        foreach ($links as $link) {
            if (strpos($link, 'rel="next"') !== false) {
                preg_match('/page_info=(.*?)[>;]/', $link, $matches);
                return $matches[1] ?? null;
            }
        }
        return null;
    }
}
