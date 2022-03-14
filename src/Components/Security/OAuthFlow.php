<?php


namespace nickdnk\OpenAPI\Components\Security;

use JetBrains\PhpStorm\Pure;

class OAuthFlow
{

    const TYPE_IMPLICIT           = 'implicit';
    const TYPE_AUTHORIZATION_CODE = 'authorization_code';
    const TYPE_CLIENT_CREDENTIALS = 'client_credentials';
    const TYPE_PASSWORD           = 'password';

    private string $type;
    private array $scopes;
    private ?string $tokenUrl, $authorizationUrl, $refreshUrl;

    /**
     * Provide scopes as [$key => $value], $key being the name of the access level and $value being the description.
     * If your implementation uses no scopes simply pass an empty array.
     *
     * @link https://swagger.io/docs/specification/authentication/oauth2/
     *
     * @param string|null $authorizationUrl
     * @param string|null $tokenUrl
     * @param array $scopes
     * @param string|null $refreshUrl
     * @param string $type
     */
    #[Pure]
    private function __construct(?string $authorizationUrl, ?string $tokenUrl, array $scopes, ?string $refreshUrl,
                                 string  $type
    )
    {

        $this->authorizationUrl = $authorizationUrl;
        $this->tokenUrl = $tokenUrl;
        $this->refreshUrl = $refreshUrl;
        $this->scopes = $scopes;
        $this->type = $type;
    }

    /**
     * Describes an implicit flow.
     *
     * @param string $authorizationUrl
     * @param string[] $scopes
     * @param string|null $refreshUrl
     *
     * @return static
     */
    #[Pure]
    final public static function implicit(string $authorizationUrl, array $scopes, ?string $refreshUrl): self
    {

        return new self($authorizationUrl, null, $scopes, $refreshUrl, self::TYPE_IMPLICIT);

    }

    /**
     * Describes an authorization code flow.
     *
     * @param string $authorizationUrl
     * @param string $tokenUrl
     * @param string[] $scopes
     * @param string|null $refreshUrl
     *
     * @return static
     */
    #[Pure]
    final public static function authorizationCode(string  $authorizationUrl, string $tokenUrl, array $scopes,
                                                   ?string $refreshUrl
    ): self
    {

        return new self($authorizationUrl, $tokenUrl, $scopes, $refreshUrl, self::TYPE_AUTHORIZATION_CODE);

    }

    /**
     * Describes a password flow.
     *
     * @param string $tokenUrl
     * @param string[] $scopes
     * @param string|null $refreshUrl
     *
     * @return static
     */
    #[Pure]
    final public static function password(string $tokenUrl, array $scopes, ?string $refreshUrl): self
    {

        return new self(null, $tokenUrl, $scopes, $refreshUrl, self::TYPE_PASSWORD);

    }

    /**
     * Describes a client credentials flow.
     *
     * @param string $tokenUrl
     * @param string[] $scopes
     * @param string|null $refreshUrl
     *
     * @return static
     */
    #[Pure]
    final public static function clientCredentials(string $tokenUrl, array $scopes, ?string $refreshUrl): self
    {

        return new self(null, $tokenUrl, $scopes, $refreshUrl, self::TYPE_CLIENT_CREDENTIALS);

    }

    #[Pure]
    final public function getAuthorizationUrl(): string
    {

        return $this->authorizationUrl;
    }

    #[Pure]
    final public function getTokenUrl(): string
    {

        return $this->tokenUrl;
    }

    #[Pure]
    final public function getRefreshUrl(): ?string
    {

        return $this->refreshUrl;
    }

    /**
     * @return string[]
     */
    #[Pure]
    final public function getScopes(): array
    {

        return $this->scopes;
    }

    #[Pure]
    final public function getType(): string
    {

        return $this->type;
    }

}