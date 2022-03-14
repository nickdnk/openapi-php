<?php


namespace nickdnk\OpenAPI\Components\Security;

use JetBrains\PhpStorm\Pure;

class RequiredSecurityScheme

{

    /** @var string[]|null */
    private ?array $scopes;
    private string $title;

    #[Pure]
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
     * @param string[] $scopes
     *
     * @return RequiredSecurityScheme
     */
    #[Pure]
    public static function forOAuth(string $title, array $scopes): self
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
    #[Pure]
    public static function forAny(string $title): self
    {

        return new self($title, null);
    }

    #[Pure]
    public function getTitle(): string
    {

        return $this->title;
    }

    /**
     * @return string[]|null
     */
    #[Pure]
    public function getScopes(): ?array
    {

        return $this->scopes;
    }

}