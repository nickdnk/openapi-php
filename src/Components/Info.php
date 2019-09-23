<?php


namespace nickdnk\OpenAPI\Components;

use JsonSerializable;

class Info implements JsonSerializable
{

    private $title, $description, $termsOfService, $contact, $license, $version, $logo;

    /**
     * Info constructor.
     *
     * @param string       $title
     * @param string       $version
     * @param string|null  $description
     * @param string|null  $termsOfService
     * @param Contact|null $contact
     * @param License|null $license
     * @param string|null  $logo
     */
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

    /**
     * @return string
     */
    final public function getTitle(): string
    {

        return $this->title;
    }

    /**
     * @return string|null
     */
    final public function getDescription(): ?string
    {

        return $this->description;
    }

    /**
     * @return string|null
     */
    final public function getTermsOfService(): ?string
    {

        return $this->termsOfService;
    }

    /**
     * @return Contact|null
     */
    final public function getContact(): ?Contact
    {

        return $this->contact;
    }

    /**
     * @return License|null
     */
    final public function getLicense(): ?License
    {

        return $this->license;
    }

    /**
     * @return string
     */
    final public function getVersion(): string
    {

        return $this->version;
    }

    /**
     * @return string|null
     */
    final public function getLogo(): ?string
    {

        return $this->logo;
    }


    /**
     * Specify data which should be serialized to JSON
     *
     * @link  https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
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