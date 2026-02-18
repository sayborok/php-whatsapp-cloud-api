<?php

namespace WhatsApp\Http;

use WhatsApp\Config\Config;
use WhatsApp\Exceptions\WhatsAppException;

/**
 * Main Client class for WhatsApp Cloud API using native PHP Curl
 */
class WhatsAppClient
{
    private Config $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * Send a request to the WhatsApp API
     */
    public function sendRequest(string $endpoint, string $method = 'POST', array $data = [], bool $isMedia = false): array
    {
        $url = $this->config->getBaseUrl() . $endpoint;

        // Use upload URL for media uploads
        if ($isMedia && $endpoint === '/media') {
            $url = $this->config->getUploadUrl();
        }

        $headers = [
            'Authorization: Bearer ' . $this->config->getAccessToken(),
            'Content-Type: application/json'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

        if ($method === 'POST' && !empty($data)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            throw new WhatsAppException("Curl error: $error");
        }

        $decodedResponse = json_decode($response, true);

        if ($httpCode >= 400) {
            $errorMessage = $decodedResponse['error']['message'] ?? 'Unknown API Error';
            $errorCode = $decodedResponse['error']['code'] ?? $httpCode;
            throw new WhatsAppException($errorMessage, $errorCode, $decodedResponse);
        }

        return $decodedResponse ?: [];
    }

    /**
     * Handle Media Uploads separately due to Multipart requirements
     */
    public function uploadMedia(string $filePath, string $type): array
    {
        $url = $this->config->getUploadUrl();

        $headers = [
            'Authorization: Bearer ' . $this->config->getAccessToken()
        ];

        $ch = curl_init();

        if (class_exists('CURLFile')) {
            $file = new \CURLFile($filePath);
        } else {
            $file = '@' . realpath($filePath);
        }

        $data = [
            'file' => $file,
            'type' => $type,
            'messaging_product' => 'whatsapp'
        ];

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            throw new WhatsAppException("Curl error during upload: $error");
        }

        $decodedResponse = json_decode($response, true);

        if ($httpCode >= 400) {
            $errorMessage = $decodedResponse['error']['message'] ?? 'Unknown Upload Error';
            throw new WhatsAppException($errorMessage, $httpCode, $decodedResponse);
        }

        return $decodedResponse;
    }

    /**
     * Download media from WhatsApp URL
     */
    public function downloadMedia(string $mediaUrl): string
    {
        $headers = [
            'Authorization: Bearer ' . $this->config->getAccessToken()
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $mediaUrl);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        $result = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            throw new WhatsAppException("Curl error during download: $error");
        }

        return $result;
    }
}
