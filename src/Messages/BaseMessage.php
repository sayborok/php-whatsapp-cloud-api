<?php

namespace WhatsApp\Messages;

/**
 * Abstract Base Message class
 */
abstract class BaseMessage
{
    protected string $to;
    protected string $type;

    public function __construct(string $to)
    {
        $this->to = $to;
    }

    /**
     * Get the JSON-serializable body of the message
     */
    abstract public function getBody(): array;

    /**
     * Common structure for all messages
     */
    public function toArray(): array
    {
        return array_merge([
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $this->to,
            'type' => $this->type
        ], $this->getBody());
    }
}
