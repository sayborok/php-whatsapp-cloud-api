<?php

namespace WhatsApp\Messages;

/**
 * Template Message class
 */
class TemplateMessage extends BaseMessage
{
    private string $templateName;
    private string $languageCode;
    private array $components;

    public function __construct(string $to, string $templateName, string $languageCode = 'en_US', array $components = [])
    {
        parent::__construct($to);
        $this->type = 'template';
        $this->templateName = $templateName;
        $this->languageCode = $languageCode;
        $this->components = $components;
    }

    public function getBody(): array
    {
        $template = [
            'name' => $this->templateName,
            'language' => [
                'code' => $this->languageCode
            ]
        ];

        if (!empty($this->components)) {
            $template['components'] = $this->components;
        }

        return ['template' => $template];
    }

    /**
     * Helper to add a component (body, header, button)
     */
    public function addComponent(string $type, array $parameters, ?string $subType = null, ?string $index = null): void
    {
        $component = [
            'type' => $type,
            'parameters' => $parameters
        ];

        if ($subType)
            $component['sub_type'] = $subType;
        if ($index !== null)
            $component['index'] = $index;

        $this->components[] = $component;
    }
}
