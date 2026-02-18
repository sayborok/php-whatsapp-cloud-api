<?php

namespace WhatsApp\Messages;

/**
 * Interactive Message class (Buttons, Lists)
 */
class InteractiveMessage extends BaseMessage
{
    private array $interactiveData;

    public function __construct(string $to, array $interactiveData)
    {
        parent::__construct($to);
        $this->type = 'interactive';
        $this->interactiveData = $interactiveData;
    }

    /**
     * Helper to create a Reply Buttons message
     */
    public static function createButtons(string $to, string $bodyText, array $buttons, ?string $headerText = null, ?string $footerText = null): self
    {
        $data = [
            'type' => 'button',
            'body' => ['text' => $bodyText],
            'action' => ['buttons' => []]
        ];

        foreach ($buttons as $id => $title) {
            $data['action']['buttons'][] = [
                'type' => 'reply',
                'reply' => ['id' => $id, 'title' => $title]
            ];
        }

        if ($headerText)
            $data['header'] = ['type' => 'text', 'text' => $headerText];
        if ($footerText)
            $data['footer'] = ['text' => $footerText];

        return new self($to, $data);
    }

    /**
     * Helper to create a List message
     */
    public static function createList(string $to, string $buttonText, string $bodyText, array $sections, ?string $headerText = null, ?string $footerText = null): self
    {
        $data = [
            'type' => 'list',
            'body' => ['text' => $bodyText],
            'action' => [
                'button' => $buttonText,
                'sections' => $sections
            ]
        ];

        if ($headerText)
            $data['header'] = ['type' => 'text', 'text' => $headerText];
        if ($footerText)
            $data['footer'] = ['text' => $footerText];

        return new self($to, $data);
    }

    public function getBody(): array
    {
        return ['interactive' => $this->interactiveData];
    }
}
