<?php


namespace nickdnk\OpenAPI\Components;

use JetBrains\PhpStorm\Pure;
use JsonSerializable;

class Info implements JsonSerializable
{

    private ?string $logo, $description, $termsOfService;
    private string $version, $title;
    private ?License $license;
    private ?Contact $contact;

    #[Pure]
    public function __construct(string  $title, string $version, ?string $description = null,
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

    #[Pure]
    final public function getTitle(): string
    {

        return $this->title;
    }

    #[Pure]
    final public function getDescription(): ?string
    {

        return $this->description;
    }

    #[Pure]
    final public function getTermsOfService(): ?string
    {

        return $this->termsOfService;
    }

    #[Pure]
    final public function getContact(): ?Contact
    {

        return $this->contact;
    }

    #[Pure]
    final public function getLicense(): ?License
    {

        return $this->license;
    }

    #[Pure]
    final public function getVersion(): string
    {

        return $this->version;
    }

    #[Pure]
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