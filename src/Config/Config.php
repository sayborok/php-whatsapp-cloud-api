<?php

namespace WhatsApp\Config;

/**
 * Configuration class for WhatsApp Cloud API
 */
class Config {
    private string $accessToken;
    private string $phoneNumberId;
    private string $version;
    private string $baseUrl = 'https://graph.facebook.com';

    public function __construct(string $accessToken, string $phoneNumberId, string $version = 'v18.0') {
        $this->accessToken = $accessToken;
        $this->phoneNumberId = $phoneNumberId;
        $this->version = $version;
    }

    public function getAccessToken(): string {
        return $this->accessToken;
    }

    public function getPhoneNumberId(): string {
        return $this->phoneNumberId;
    }

    public function getVersion(): string {
        return $this->version;
    }

    public function getBaseUrl(): string {
        return "{$this->baseUrl}/{$this->version}/{$this->phoneNumberId}";
    }

    public function getUploadUrl(): string {
        return "{$this->baseUrl}/{$this->version}/{$this->phoneNumberId}/media";
    }
}
