<?php

namespace App\Services;

use Google\Auth\Credentials\ServiceAccountCredentials;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FirebaseNotificationService
{
    protected string $projectId;
    protected string $credentialsPath;

    public function __construct()
    {
        $this->projectId = config('services.firebase.project_id');
        $this->credentialsPath = storage_path('app/firebase.json');
    }

    private function getAccessToken(): string
    {
        $scopes = ['https://www.googleapis.com/auth/firebase.messaging'];

        $creds = new ServiceAccountCredentials(
            $scopes,
            json_decode(file_get_contents($this->credentialsPath), true)
        );

        $token = $creds->fetchAuthToken();

        return $token['access_token'];
    }

    public function sendToToken(string $token, string $title, string $body, array $data = [])
    {
        if (!$token) {
            return;
        }

        $accessToken = $this->getAccessToken();

        $url = "https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:send";

        $payload = [
            'message' => [
                'token' => $token,
                'notification' => [
                    'title' => $title,
                    'body'  => $body,
                ],
                'data' => $data
            ]
        ];

        Http::withToken($accessToken)
            ->post($url, $payload);
    }
}
