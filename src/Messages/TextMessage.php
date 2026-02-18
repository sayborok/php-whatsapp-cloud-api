<?php

namespace WhatsApp\Messages;

/**
 * Text Message class
 */
class TextMessage extends BaseMessage
{
    private string $text;
    private bool $previewUrl;

    public function __construct(string $to, string $text, bool $previewUrl = false)
    {
        parent::__construct($to);
        $this->type = 'text';
        $this->text = $text;
        $this->previewUrl = $previewUrl;
    }

    public function getBody(): array
    {
        return [
            'text' => [
                'body' => $this->text,
                'preview_url' => $this->previewUrl
            ]
        ];
    }
}
