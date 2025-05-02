<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use App\Models\{ClientPreference, DeviceToken, Order, Setting};
use Illuminate\Support\Arr;

class FirebaseService
{
    //public $projectId;
    protected $client;
    public $project_id,$firebase_json_file;
    //protected $serviceAccount;

    public function __construct()
    {
        //$this->projectId = config('services.firebase.project_id');
        $this->client = new Client();
   
        //$this->serviceAccount = json_decode(file_get_contents(public_path("firebase/fcm.json")), true);
    }

    public static function getAccessToken()
    {
        $client = new Client();
        $setting = Setting::first();
        $serviceAccount = json_decode(file_get_contents($setting->firebase_json_file), true);
        $project_id = $serviceAccount['project_id'];
        $now = time();
        $payload = [
            'iss' => $serviceAccount['client_id'],
            'sub' => $serviceAccount['client_id'],
            'aud' => 'https://oauth2.googleapis.com/token',
            'iat' => $now,
            'exp' => $now + 3600,
            'scope' => 'https://www.googleapis.com/auth/firebase.messaging'
        ];

        $header = ['alg' => 'RS256', 'typ' => 'JWT'];
        $base64UrlHeader = Self::base64UrlEncode(json_encode($header));
        $base64UrlPayload = Self::base64UrlEncode(json_encode($payload));

        $signature = '';
        openssl_sign($base64UrlHeader . '.' . $base64UrlPayload, $signature, $serviceAccount['private_key'], 'sha256');
        $base64UrlSignature = Self::base64UrlEncode($signature);

        $jwt = $base64UrlHeader . '.' . $base64UrlPayload . '.' . $base64UrlSignature;
        // dd($jwt);
        try {
            $response = $client->post('https://oauth2.googleapis.com/token', [
                'form_params' => [
                    'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                    'assertion' => $jwt,
                ],
            ]);

            $data = json_decode($response->getBody(), true);
            \Log::warning($data);
            return $data['access_token'];
        } catch (RequestException $e) {
            \Log::warning($e->getMessage().$e->getLine());
            return null;
        }
    }

    public static function sendNotification($data, $is_vendor = 0)
    {
        $client = new Client();
        $accessToken = self::getAccessToken();
        $setting = Setting::first();
        // Get project ID from the service account file
        $serviceAccount = json_decode(file_get_contents($setting->firebase_json_file), true);
        $projectId = $serviceAccount['project_id'];

        $url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";

        if (!$accessToken) {
            return ['error' => 'Unable to fetch access token'];
        }

        try {
            $results = [];
            foreach ($data['registration_ids'] as $token) {
                $message = [
                    'token' => $token,
                    'notification' => [
                        'title' => $data['notification']['title'] ?? '',
                        'body' => $data['notification']['body'] ?? '',
                        'image' => $data['notification']['image'] ?? '',
                    ],
                    'android' => [
                        'priority' => $data['priority'] ?? 'HIGH',
                        'notification' => [
                            'icon' => $data['notification']['icon'] ?? '',
                            'sound' => $data['notification']['sound'] ?? 'default',
                            'click_action' => $data['notification']['click_action'] ?? '',
                            'channel_id' => $data['notification']['android_channel_id'] ?? 'default-channel-id',
                        ],
                    ],
                    'apns' => [
                        'headers' => [
                            'apns-priority' => '10',
                        ],
                        'payload' => [
                            'aps' => [
                                'alert' => [
                                    'title' => $data['notification']['title'] ?? '',
                                    'body' => $data['notification']['body'] ?? '',
                                ],
                                'sound' => $data['notification']['sound'] ?? 'default',
                            ],
                        ],
                    ],
                ];

                if (isset($data['data'])) {
                    $message['data'] = $data['data'];
                }

                try {
                    $response = $client->post($url, [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $accessToken,
                            'Content-Type' => 'application/json',
                        ],
                        'json' => [
                            'validate_only' => false,
                            'message' => $message,
                        ],
                    ]);

                    $results[] = [
                        'status' => 'fulfilled',
                        'body' => (string)$response->getBody(),
                    ];
                } catch (RequestException $e) {
                    \Log::error('FCM Notification Error: ' . $e->getMessage());
                    $results[] = [
                        'status' => 'rejected',
                        'reason' => $e->getMessage(),
                    ];
                }
            }

            return $results;
        } catch (RequestException $e) {
            \Log::error('FCM Notification Error: ' . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }

    public static function sendNotification_old(array $data)
    {
        // Fetch the device tokens for the user
        $devices = DeviceToken::whereNotNull('device_token')
            ->pluck('device_token')
            ->toArray();
        // dd($devices);
        if (empty($devices)) {
            \Log::warning('No device tokens found for user_id: ' . $data['user_id']);
            return ['error' => 'No devices found'];
        }
        $timeToLive = null;
        $priority = "high";
        $sound = "default";
        $payload = [
            'message' => [
                'token' => $devices[0],
                'notification' => [
                    'title' => 'Testing',
                    'body' => 'fghfgh',
                ],
                'apns' => [
                    'headers' => [
                        'apns-priority' => ($priority == 'high') ? '10' : '5',
                        // 'apns-expiration' => $timeToLive ? strval(time() + $timeToLive) : null,
                    ],
                    'payload' => [
                        'aps' => [
                            'sound' => $sound,
                            'content-available' => 1,
                        ],
                        'customData' => null,
                    ],
                ],
                'data' => null,
            ]
        ];
        \Log::info('payload');
        \Log::info($payload);
        \Log::info('payload');
        try {
            $client = new Client();
            $accessToken = self::getAccessToken();  // Get Firebase access token
            //  dd($accessToken);
            $setting = Setting::first();
            $serviceAccount = json_decode(file_get_contents($setting->firebase_json_file), true);
            $projectId = $serviceAccount['project_id'];

            $url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";
            $response = $client->post('https://fcm.googleapis.com/v1/projects/social-ect/messages:send', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                ],
                'json' => $payload,  // Send the corrected payload
            ]);
            \Log::info('response');
            \Log::info($response);
            // dd($response);
            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            \Log::info($e);
            \Log::error('FCM Notification Error: ' . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }   

    protected static function base64UrlEncode($data)
    {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
    }
}
