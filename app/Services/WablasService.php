<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class WablasService
{
    protected $apiToken;
    protected $baseUrl;
    protected $groupId; // Predefined group ID or phone number

    public function __construct()
    {
        $this->apiToken = env('WABLAS_API_TOKEN', 'LhSM6HbNF3ZMHP8XiOI1jPZFSWSA3cbVbAmy3bf2qYYQYRqxOd7hC2C');
        $this->baseUrl = 'https://bdg.wablas.com/api/v2';
        $this->groupId = env('WABLAS_GROUP_ID', '120363419294432193'); // Set this in .env or hardcode here
    }

    /**
     * Send a message to the predefined group or phone.
     *
     * @param string $message The message content
     * @return mixed Wablas API response
     */
    public function sendMessageToGroup($message, $imageUrl)
    {
        $url = "{$this->baseUrl}/send-message";

        // Payload untuk mengirim pesan dengan gambar
        $payload = [
            "secret" => env('WABLAS_SECRET'),
            "data" => [
                [
                    'phone' => $this->groupId, // Menggunakan ID grup WhatsApp
                    'message' => $message, // Pesan teks yang ingin dikirim
                    'image' => $imageUrl, // URL gambar yang bisa diakses
                    'isGroup' => 'true', // Menunjukkan bahwa pesan ini ditujukan ke grup
                ]
            ]
        ];

        // Inisialisasi cURL untuk mengirim permintaan
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            "Authorization: {$this->apiToken}",
            "Content-Type: application/json"
        ]);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

        $result = curl_exec($curl);
        curl_close($curl);

        // Log respons dari API untuk debugging
        Log::info('Wablas API response:', ['response' => $result]);

        return json_decode($result, true); // Mengembalikan respons API yang sudah didekode
    }

    public function kirimGambarWablas($groupId, $imageUrl, $caption = '')
    {
        $curl = curl_init();

        $token = "LhSM6HbNF3ZMHP8XiOI1jPZFSWSA3cbVbAmy3bf2qYYQYRqxOd7hC2C";
        $secret_key = "5LCZja2b";

        $payload = [
            "data" => [
                [
                    'group_id' => $groupId,
                    'image' => $imageUrl,
                    'caption' => $caption,
                ]
            ]
        ];

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://bdg.wablas.com/api/v2/group/image",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => [
                "Authorization: $token.$secret_key",
                "Content-Type: application/json"
            ],
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            return response()->json(['status' => false, 'message' => 'Curl error', 'error' => $err], 500);
        }

        $decoded = json_decode($response, true);
        return response()->json($decoded ?? ['status' => false, 'message' => 'Invalid JSON response', 'raw' => $response]);
    }
}
