<?php


namespace nickdnk\OpenAPI\Components\Security;

class RequiredSecurityScheme

{

    private $title, $scopes;

    /**
     * RequiredSecurityScheme constructor.
     *
     * @param $title
     * @param $scopes
     */
    private function __construct(string $title, ?array $scopes)
    {

        $this->title = $title;
        $this->scopes = $scopes;
    }

    /**
     * Returns a requirement for an OAuth SecurityScheme.
     *
     * The scopes should be a simple string array of the required scopes, such as ['scope1', 'scope2...] etc.
     *
     * @param string $title
     * @param array  $scopes
     *
     * @return RequiredSecurityScheme
     */
    public static function forOAuth(string $title, array $scopes)
    {

        return new self($title, $scopes);
    }

    /**
     * Returns a requirement for a non-OAuth SecurityScheme.
     *
     * @param string $title
     *
     * @return RequiredSecurityScheme
     */
    public static function forAny(string $title)
    {

        return new self($title, null);
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {

        return $this->title;
    }

    /**
     * @return array|null
     */
    public function getScopes(): ?array
    {

        return $this->scopes;
    }

}