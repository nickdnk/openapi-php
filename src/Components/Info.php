<?php


namespace nickdnk\OpenAPI\Components;

use JsonSerializable;

class Info implements JsonSerializable
{

    private $title, $description, $termsOfService, $contact, $license, $version, $logo;

    public function __construct(string $title, string $version, ?string $description = null,
                                ?string $termsOfService = null, ?Contact $contact = null, ?License $license = null, ?string $logo = null
    )
    {

        $this->title = $title;
        $this->description = $description;
        $this->termsOfService = $termsOfService;
        $this->contact = $contact;
        $this->license = $license;
        $this->version = $version;
        $this->logo = $logo;
    }

    final public function getTitle(): string
    {

        return $this->title;
    }

    final public function getDescription(): ?string
    {

        return $this->description;
    }

    final public function getTermsOfService(): ?string
    {

        return $this->termsOfService;
    }

    final public function getContact(): ?Contact
    {

        return $this->contact;
    }

    final public function getLicense(): ?License
    {

        return $this->license;
    }

    final public function getVersion(): string
    {

        return $this->version;
    }

    final public function getLogo(): ?string
    {

        return $this->logo;
    }

    public function jsonSerialize(): array
    {

        $return = [
            'title'   => $this->title,
            'version' => $this->version
        ];

        if ($this->description) {
            $return['description'] = $this->description;
        }

        if ($this->termsOfService) {
            $return['termsOfService'] = $this->termsOfService;
        }

        if ($this->contact) {
            $return['contact'] = $this->contact;
        }

        if ($this->license) {
            $return['license'] = $this->license;
        }

        if ($this->logo) {
            $return['x-logo'] = [
                'url' => $this->logo
            ];
        }

        return $return;

    }
}