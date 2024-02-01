<?php

namespace App\Services;

use GuzzleHttp\Client;

class RajaOngkirService
{
    protected $client;
    protected $api_key;
    protected $base_url;

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->api_key = config('services.rajaongkir.key');
        $this->base_url = config('services.rajaongkir.base_url');
    }

    public function getProvinces()
    {
        $response = $this->client->request('GET', $this->base_url . '/province', [
            'query' => ['key' => $this->api_key],
        ]);

        return json_decode($response->getBody(), true)['rajaongkir']['results'];
    }

    public function getCities($province_id)
    {
        $response = $this->client->request('GET', $this->base_url . '/city', [
            'query' => [
                'key' => $this->api_key,
                'province' => $province_id,
            ],
        ]);

        return json_decode($response->getBody(), true)['rajaongkir']['results'];
    }
}
