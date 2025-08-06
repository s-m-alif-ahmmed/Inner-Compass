<?php
namespace App\Services;
use Kreait\Firebase\Factory;

class FCMService{
    protected $messaging;

    public function __construct()
    {
        $factory = (new Factory)->withServiceAccount(config('services.firebase.credentials_file'));
        $this->messaging = $factory->createMessaging();
    }

    public function sendMessage($token, $title, $body, $data = [])
    {
        $message = [
            'token' => $token,
            'notification' => [
                'title' => $title,
                'body' => $body,
            ],
            'data' => array_merge($data, [
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
            ]),
        ];

        $this->messaging->send($message);
    }
}

// $fcmService = new FCMService();
// $fcmService->sendNotification(
//     $jobpost->user->firebaseTokens->token,
//     'Job Post',
//     'You have a new job post',
//     ['job_post_id' => $jobpost->id]
// );
