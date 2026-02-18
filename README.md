# WhatsApp Cloud API PHP Wrapper (Native)

A lightweight, object-oriented, and modern PHP wrapper for the **WhatsApp Cloud API (v18.0+)**. This library is designed to be "native," meaning it has **zero external dependencies** (no Guzzle, etc.) and relies solely on PHP's built-in `curl` extension.

## 🚀 Features

- **Native PHP**: No third-party packages required.
- **PSR-4 Compliant**: Easy integration with modern PHP projects.
- **Comprehensive Message Support**:
    - 📝 **Text Messages**: With optional link previews.
    - 🖼️ **Media Messages**: Image, Video, Audio, Document, Sticker (via ID or URL).
    - 🔘 **Interactive Messages**: Reply Buttons and List Messages.
    - 📋 **Template Messages**: Support for variables and dynamic components.
    - 📍 **Location & Contacts**: Send coordinates and vCards.
- **Media Management**: Upload files to WhatsApp servers and download received media.
- **Webhook Handler**: Easy verification and parsing of incoming notifications.
- **Robust Error Handling**: Specialized exceptions for API errors.

## 🛠 Installation

### Via Composer (Recommended)

Add the library to your `composer.json` or run:

```bash
composer require sayborok/php-whatsapp-cloud-api
```

### Manual Installation

If you are not using Composer, copy the `src/` directory to your project and include the files using the `index.php` model as a reference.

## 📖 Quick Start

### 1. Basic Configuration

```php
use WhatsApp\Config\Config;
use WhatsApp\Http\WhatsAppClient;

$config = new Config(
    'YOUR_ACCESS_TOKEN',
    'YOUR_PHONE_NUMBER_ID',
    'v18.0' // Version is optional, defaults to v18.0
);

$client = new WhatsAppClient($config);
```

### 2. Sending a Text Message

```php
use WhatsApp\Messages\TextMessage;

try {
    $message = new TextMessage('905001234567', 'Hello from PHP!');
    $response = $client->sendRequest('/messages', 'POST', $message->toArray());
    print_r($response);
} catch (\WhatsApp\Exceptions\WhatsAppException $e) {
    echo "Error: " . $e->getMessage();
}
```

### 3. Sending Media (Image/Document)

```php
use WhatsApp\Messages\MediaMessage;

// Send via URL
$image = new MediaMessage('905001234567', 'image', 'https://example.com/photo.jpg', false, 'Check this out!');
$client->sendRequest('/messages', 'POST', $image->toArray());

// Upload local file first, then send via ID
$upload = $client->uploadMedia('path/to/local_image.png', 'image');
$mediaId = $upload['id'];

$imageById = new MediaMessage('905001234567', 'image', $mediaId, true);
$client->sendRequest('/messages', 'POST', $imageById->toArray());
```

### 4. Interactive Reply Buttons

```php
use WhatsApp\Messages\InteractiveMessage;

$buttons = [
    'btn_1' => 'Yes, please!',
    'btn_2' => 'Maybe later'
];

$message = InteractiveMessage::createButtons(
    '905001234567',
    'Would you like to subscribe?',
    $buttons,
    'Subscription Header', // Optional
    'Powered by PHP'        // Optional footer
);

$client->sendRequest('/messages', 'POST', $message->toArray());
```

### 5. Webhook Handling

```php
use WhatsApp\Webhooks\WebhookHandler;

$handler = new WebhookHandler();

// 1. Verify URL (Mandatory for WhatsApp setup)
if (isset($_GET['hub_challenge'])) {
    echo WebhookHandler::verify('YOUR_VERIFY_TOKEN', $_GET);
    exit;
}

// 2. Handle incoming notifications
$payload = file_get_contents('php://input');
$data = $handler->handle($payload);

if ($handler->getType($data) === 'message') {
    $incomingMessage = $data['messages'][0];
    // Process the message...
}
```

## 📂 Project Structure

- `src/Config`: API settings.
- `src/Http`: The Curl-based client.
- `src/Messages`: Classes for different message types.
- `src/Webhooks`: Logic for handling incoming data.
- `src/Exceptions`: Custom exception handling.

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.
