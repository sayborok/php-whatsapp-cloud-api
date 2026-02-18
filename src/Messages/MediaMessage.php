<?php

namespace WhatsApp\Messages;

/**
 * Media Message class for Image, Video, Audio, Document, Sticker
 */
class MediaMessage extends BaseMessage
{
    private string $idOrUrl;
    private bool $isId;
    private ?string $caption;
    private ?string $filename;

    public function __construct(string $to, string $type, string $idOrUrl, bool $isId = true, ?string $caption = null, ?string $filename = null)
    {
        parent::__construct($to);
        $this->type = $type; // image, video, audio, document, sticker
        $this->idOrUrl = $idOrUrl;
        $this->isId = $isId;
        $this->caption = $caption;
        $this->filename = $filename;
    }

    public function getBody(): array
    {
        $mediaData = $this->isId ? ['id' => $this->idOrUrl] : ['link' => $this->idOrUrl];

        if ($this->caption) {
            $mediaData['caption'] = $this->caption;
        }

        if ($this->filename) {
            $mediaData['filename'] = $this->filename;
        }

        return [
            $this->type => $mediaData
        ];
    }
}
