<?php

namespace App\Services;

use App\Models\AffilateIntegration;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class TrackierService
{
    protected $client;
    // protected $apiUrl = 'https://api.trackier.com';
    protected $apiUrl ='https://stoplight.io/mocks/trackier/perf-pub-api-docs/163865274';
    protected $version = 'v2';
    protected $apiKey = ''; // replace with your API key

    public function __construct()
    {
        $AffilateIntegration = AffilateIntegration::where('provider_name','vcommission')->first();
        $this->client = new Client();
        $this->apiKey = $AffilateIntegration->api_key;
    }

    public function getConversions(array $requestParams = [])
    {
        $queryParams = [];

        // Map request parameters to query params
        $allowedParams = ['end', 'start', 'click_id', 'limit', 'p1', 'p2', 'p3', 'p4', 'p5', 'page', 'status'];

        foreach ($allowedParams as $param) {
            if (!empty($requestParams[$param])) {
                $queryParams[$param] =  $param.'='.$requestParams[$param];
            }
        }
       $query_string = NULL;
       if(count($queryParams)>0)
       {
        $query_string = '?'.implode('&',$queryParams);
       }
       
        try {
            $url = $this->apiUrl.'/'.$this->version.'/publishers/conversions';
            $response = $this->client->get($url.$query_string, [
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
