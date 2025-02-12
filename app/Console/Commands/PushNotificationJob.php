<?php

namespace App\Console\Commands;

use App\Models\Notifcation;
use App\Models\PushNotification;
use App\Models\User;
use App\Models\UserDevice;
use App\Services\FirebaseService;
use Illuminate\Console\Command;

class PushNotificationJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'push-notification:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notification to all users';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $time = now()->format('Y-m-d H:i:s');
        $users = User::whereNotNull('id')->get();
        $pushnotification = PushNotification::where('status',0)->where('schedule_datetime', '<=', $time)
        ->get();
    
        foreach ($pushnotification as $notification) {
            $tokens = [];
            foreach ($users as $user) {
                $deviceToken = UserDevice::where('user_id',$user->id)->latest();
                if ($deviceToken && $deviceToken->device_token) {
                    $tokens[] = $deviceToken->device_token;
                    // Create a new notification entry for the user
                    Notifcation::create([
                        'user_id' => $user->id,
                        'push_notification_id' => $notification->id,
                        'title' => $notification->title,
                        'message' => ($notification->type == 0) ? $notification->description : ($notification->type == 1 ? $notification->email_body:$notification->message),
                        'image_url' => $notification->image_url,
                        'link_url' => $notification->push_url_option_value,
                        'is_read' => false,
                    ]);
                }
            }

            // If there are tokens, send the notifications using Firebase
            if (!empty($tokens)) {
                $data = [
                    "registration_ids" => $tokens,
                    "notification" => [
                        'title' => $notification->title,
                        'body' =>($notification->type == 0) ? $notification->description : ($notification->type == 1 ? $notification->email_body:$notification->message),
                        'image' => $notification->image_url,
                    ],
                    "data" => [
                        'click_action' => $notification->push_url_option_value,
                    ],
                    "priority" => "high",
                ];
                // Send the notification via Firebase
                try {
                    FirebaseService::sendNotification($data);
                } catch (\Exception $e) {
                    $this->error('Failed to send notification: ' . $e->getMessage());
                    continue;
                }
                // Mark the notification as sent
                $notification->update(['status' => 1]);
            }
        }

        // Output success message
        $this->info('Push Notification sent successfully.');

    }
}
