<?php

namespace App\Jobs;

use App\Models\PushNotification;
use App\Models\UserDevice;
use App\Services\FirebaseService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PushNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $notification;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(PushNotification $notification)
    {
        $this->notification = $notification;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            // Get all device tokens
            $tokens = UserDevice::whereNotNull('device_token')
                ->pluck('device_token')
                ->toArray();
            \Log::warning(['tokens'=>$tokens]);
            if (empty($tokens)) {
                \Log::warning('No device tokens found for sending notification');
                $this->notification->update(['status' => 2]); // Failed
                return;
            }

            // Prepare notification data
            $data = [
                'registration_ids' => $tokens,
                'notification' => [
                    'title' => $this->notification->title,
                    'body' => $this->notification->description,
                    'image' => $this->notification->image_url,
                ],
                'data' => [
                    'type' => $this->notification->type,
                    'click_action' => $this->notification->push_url_option_value ?? '',
                ],
                'priority' => 'high',
            ];

            // Send notification via Firebase
            $result = FirebaseService::sendNotification($data);

            // Update notification status
            $this->notification->update(['status' => 1]); // Sent

            \Log::info('Push notification sent successfully', [
                'notification_id' => $this->notification->id,
                'result' => $result
            ]);

        } catch (\Exception $e) {
            \Log::error('Failed to send push notification: ' . $e->getMessage());
            $this->notification->update(['status' => 2]); // Failed
        }
    }
}
