<?php

namespace WhatsApp\Webhooks;

/**
 * Webhook Handler to parse incoming WhatsApp notifications
 */
class WebhookHandler
{

    /**
     * Verify Webhook Subscription (HUB Verification)
     */
    public static function verify(string $token, array $queryParams): ?string
    {
        $mode = $queryParams['hub_mode'] ?? null;
        $vToken = $queryParams['hub_verify_token'] ?? null;
        $challenge = $queryParams['hub_challenge'] ?? null;

        if ($mode === 'subscribe' && $token === $vToken) {
            return $challenge;
        }

        return null;
    }

    /**
     * Parse incoming payload
     */
    public function handle(string $jsonPayload): array
    {
        $data = json_decode($jsonPayload, true);

        if (!$data || !isset($data['entry'][0]['changes'][0]['value'])) {
            return [];
        }

        $value = $data['entry'][0]['changes'][0]['value'];
        $results = [
            'metadata' => $value['metadata'] ?? [],
            'contacts' => $value['contacts'] ?? [],
            'messages' => $value['messages'] ?? [],
            'statuses' => $value['statuses'] ?? []
        ];

        return $results;
    }

    /**
     * Determine notification type
     */
    public function getType(array $parsedData): string
    {
        if (!empty($parsedData['messages']))
            return 'message';
        if (!empty($parsedData['statuses']))
            return 'status';
        return 'unknown';
    }
}
