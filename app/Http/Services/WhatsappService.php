<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Http;

class WhatsappService
{
    protected $apiUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->apiUrl = env("WA_URL"); // WhatsApp API base URL
        $this->apiKey = env("WA_API_KEY"); // WhatsApp API key
    }

    /**
     * Send WhatsApp messages using HTTP API.
     *
     * @param array $recipients - Array of recipient phone numbers
     * @param array $messages - Array of message bodies to send
     * @return array|bool - API response or false on failure
     */
    public function sendMessages(array $recipients, array $messages)
    {
        $payload = $this->buildPayload($recipients, $messages);

        // Make the HTTP POST request to the WhatsApp API
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey, // Assuming Bearer token for authorization
            'Content-Type'  => 'application/json',
        ])->post($this->apiUrl, $payload);

        // Return the API response or handle error
        if ($response->successful()) {
            return $response->json();
        } else {
            return false; // Handle error as needed
        }
    }

    /**
     * Send WhatsApp message using HTTP API.
     *
     * @param string $recipient - Recipient phone number
     * @param string $message - Message body to send
     * @return array|bool - API response or false on failure
     */
    public function sendMessage($recipient, $message)
    {
        $payload = [
            'recipient_type' => 'individual',
            'to'             => $recipient,
            'type'           => 'text',
            'text'           => [
                'body' => $message,
            ],
        ];

        // Make the HTTP POST request to the WhatsApp API
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey, // Assuming Bearer token for authorization
            'Content-Type'  => 'application/json',
        ])->post($this->apiUrl, $payload);

        // Return the API response or handle error
        if ($response->successful()) {
            return $response->json();
        } else {
            return false; // Handle error as needed
        }
    }

    /**
     * Build the WhatsApp API payload.
     *
     * @param array $recipients - Array of recipient phone numbers
     * @param array $messages - Array of message bodies to send
     * @return array - API payload
     */
    protected function buildPayload(array $recipients, array $messages)
    {
        $payload = [];

        foreach ($recipients as $index => $recipient) {
            // Ensure that there are enough messages for recipients
            $message = $messages[$index] ?? $messages[0]; // Use the first message if not enough messages provided

            $payload[] = [
                'recipient_type' => 'individual',
                'to'             => $recipient,
                'type'           => 'text',
                'text'           => [
                    'body' => $message,
                ],
            ];
        }

        return $payload;
    }
}