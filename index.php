<?php

/**
 * WhatsApp Cloud API Wrapper - Usage Example
 */

require_once 'src/Config/Config.php';
require_once 'src/Exceptions/WhatsAppException.php';
require_once 'src/Http/WhatsAppClient.php';
require_once 'src/Messages/BaseMessage.php';
require_once 'src/Messages/TextMessage.php';
require_once 'src/Messages/MediaMessage.php';
require_once 'src/Messages/InteractiveMessage.php';
require_once 'src/Messages/LocationMessage.php';
require_once 'src/Messages/TemplateMessage.php';
require_once 'src/Webhooks/WebhookHandler.php';

use WhatsApp\Config\Config;
use WhatsApp\Http\WhatsAppClient;
use WhatsApp\Messages\TextMessage;
use WhatsApp\Messages\MediaMessage;
use WhatsApp\Exceptions\WhatsAppException;

// 1. Configuration
$config = new Config(
    'YOUR_ACCESS_TOKEN',
    'YOUR_PHONE_NUMBER_ID',
    'v18.0'
);

// 2. Client Initialization
$client = new WhatsAppClient($config);

// --- EXAMPLE 1: Sending Text Message ---
try {
    $textMessage = new TextMessage('905000000000', 'Merhaba! Bu bir test mesajıdır.');
    // $response = $client->sendRequest('/messages', 'POST', $textMessage->toArray());
    // print_r($response);
    echo "Text message class initialized correctly.\n";
} catch (WhatsAppException $e) {
    echo "Error: " . $e->getMessage();
}

// --- EXAMPLE 2: Uploading and Sending Media ---
try {
    // $uploadResponse = $client->uploadMedia('path/to/image.jpg', 'image');
    // $mediaId = $uploadResponse['id'];

    // $imageMessage = new MediaMessage('905000000000', 'image', 'https://example.com/image.jpg', false, 'Bu bir görseldir.');
    // $response = $client->sendRequest('/messages', 'POST', $imageMessage->toArray());
    echo "Media message class initialized correctly.\n";
} catch (WhatsAppException $e) {
    echo "Error: " . $e->getMessage();
}

echo "\nKütüphane yapısı hazır! PSR-4 autoloading kullanıyorsanız require_once satırlarına gerek kalmayacaktır.";
