<?php

use Google\Client;
use App\Models\SettingsModel;

if (!function_exists('sendFirebaseNotification')) {
    function sendFirebaseNotification($token, $title, $body, $data = [])
    {
        // Load the SettingsModel
        $SettingsModel = new SettingsModel();
        $serviceAccountSetting = $SettingsModel->getSettings();

        if (!$serviceAccountSetting['firebase_admin_json_file_content']) {
            return ['error' => 'Service account JSON content not found in settings.'];
        }

        $firebaseProjectID = json_decode($serviceAccountSetting['fcm_credentials'], true);
        $serviceAccountJson = json_decode($serviceAccountSetting['firebase_admin_json_file_content'], true);

        $url = 'https://fcm.googleapis.com/v1/projects/' . $firebaseProjectID['projectId'] . '/messages:send';

        // Create a Google Client
        $client = new Client();
        $client->setAuthConfig($serviceAccountJson); // Directly setting JSON content
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');

        // Generate an OAuth 2.0 access token
        $accessToken = $client->fetchAccessTokenWithAssertion()['access_token'];

        $message = [
            'message' => [
                'token' => $token,
                'notification' => [
                    'title' => $title,
                    'body' => $body
                ],
                'data' => $data
            ]
        ];

        $headers = [
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: application/json'
        ];

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($message),
            CURLOPT_HTTPHEADER => $headers,
        ]);

        $response = curl_exec($curl);
        $error = curl_error($curl);

        curl_close($curl);

        if ($error) {
            return ['error' => $error];
        }

        return json_decode($response, true);
    }
}
