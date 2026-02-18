<?php

namespace WhatsApp\Messages;

/**
 * Location Message class
 */
class LocationMessage extends BaseMessage
{
    private float $latitude;
    private float $longitude;
    private ?string $name;
    private ?string $address;

    public function __construct(string $to, float $latitude, float $longitude, ?string $name = null, ?string $address = null)
    {
        parent::__construct($to);
        $this->type = 'location';
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->name = $name;
        $this->address = $address;
    }

    public function getBody(): array
    {
        $location = [
            'latitude' => $this->latitude,
            'longitude' => $this->longitude
        ];

        if ($this->name)
            $location['name'] = $this->name;
        if ($this->address)
            $location['address'] = $this->address;

        return ['location' => $location];
    }
}
