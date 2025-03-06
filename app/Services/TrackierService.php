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

    public function getConversions($unique_source_id)
    {
    
        try {
            $url = $this->apiUrl.'/'.$this->version.'/publishers/conversions';
            $response = $this->client->get($url.'?source='.$unique_source_id, [
                'headers' => [
                    'Accept' => 'application/json',
                    'X-Api-Key' => $this->apiKey,
                ],
            ]);
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            Log::error('Error fetching Trackier conversions: ' . $e->getMessage());
            return ['error' => 'Failed to fetch conversions'.$e->getMessage()];
        }
    }
}
