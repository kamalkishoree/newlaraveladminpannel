<?php

namespace App\Services;

use App\Models\AffilateIntegration;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class TrackierService
{
    protected $client;
    protected $apiUrl = 'https://api.trackier.com';
    // protected $apiUrl ='https://stoplight.io/mocks/trackier/perf-pub-api-docs/163865274';
    protected $version = 'v2';
    protected $apiKey = ''; // replace with your API key

    public function __construct()
    {
        $AffilateIntegration = AffilateIntegration::where('provider_name','vcommission')->first();
        $this->client = new Client();
        $this->apiKey = $AffilateIntegration->api_key;
    }

    public function getConversions($campaign)
    {
    
        try {
            $url = $this->apiUrl . '/' . $this->version . '/publishers/conversions';
            $queryParams = [
                'source' => $campaign->unique_source_id,
                'start' => $campaign->created_at->format('Y-m-d'), // Campaign creation date
                'end' => $campaign->created_at->addDays(15)->format('Y-m-d'), // 15 days after creation
            ];
            $response = $this->client->get($url, [
                'headers' => [
                    'Accept' => 'application/json',
                    'X-Api-Key' => $this->apiKey,
                ],
                'query' => $queryParams, // Adding query parameters properly

            ]);
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            Log::error('Error fetching Trackier conversions: ' . $e->getMessage());
            Log::error('Error fetching Trackier conversions: ' . $e->getLine());
            return ['error' => 'Failed to fetch conversions'.$e->getMessage()];
        }
    }
}
