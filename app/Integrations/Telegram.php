<?php

class Telegram
{
    public static function sendMessage(string $botToken, int $chatId, string $message): void
    {
        $telegramApiUrl = "https://api.telegram.org/bot{$botToken}/sendMessage";
        $postFields = [
            'chat_id' => $chatId,
            'text'    => $message
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $telegramApiUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response     = curl_exec($ch);
        $errorCode    = curl_errno($ch);
        $errorMessage = curl_error($ch);
        curl_close($ch);

        if ($errorCode) {
            throw new Exception("Curl error: {$errorMessage}", $errorCode);
        }

        $jsonResponse = json_decode($response, true);

        if (!$jsonResponse || !$jsonResponse['ok']) {
            throw new Exception('Telegram API error: ' . ($jsonResponse['description'] ?? 'unknown error'));
        }
    }
}

