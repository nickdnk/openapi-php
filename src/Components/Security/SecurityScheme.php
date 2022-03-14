<?php


namespace nickdnk\OpenAPI\Components\Security;

use InvalidArgumentException;
use JetBrains\PhpStorm\Pure;
use JsonSerializable;

class SecurityScheme implements JsonSerializable
{

    const TYPE_API_KEY         = 'apiKey';
    const TYPE_HTTP            = 'http';
    const TYPE_OAUTH_2         = 'oauth2';
    const TYPE_OPEN_ID_CONNECT = 'openIdConnect';

    const API_KEY_IN_QUERY  = 'query';
    const API_KEY_IN_HEADER = 'header';
    const API_KEY_IN_COOKIE = 'cookie';

    const SCHEME_WWW_AUTHENTICATE    = 'WWW-Authenticate';
    const SCHEME_PROXY_AUTHORIZATION = 'Proxy-Authorization';
    const SCHEME_PROXY_AUTHENTICATE  = 'Proxy-Authenticate';
    const SCHEME_AUTHORIZATION       = 'Authorization';

    private string $securityTitle, $type;

    /** @var OAuthFlow[]  */
    private array $flows;

    private ?string $name, $description, $in, $scheme, $bearerFormat, $openIdConnectUrl;

    #[Pure]
    private function __construct(string  $securityTitle, string $type, ?string $name, ?string $in, ?string $description,
                                 ?string $scheme = null, ?string $bearerFormat = null, ?string $openIdConnectUrl = null
    )
    {

        $this->securityTitle = $securityTitle;
        $this->type = $type;
        $this->description = $description;
        $this->name = $name;
        $this->in = $in;
        $this->scheme = $scheme;
        $this->bearerFormat = $bearerFormat;
        $this->flows = [];
        $this->openIdConnectUrl = $openIdConnectUrl;

    }

    #[Pure]
    final public static function apiKey(string $securityTitle, string $name, string $in, ?string $description): self
    {

        return new self($securityTitle, self::TYPE_API_KEY, $name, $in, $description);
    }

    #[Pure]
    final public static function oAuth2(string $securityTitle, ?string $description): self
    {

        return new self($securityTitle, self::TYPE_OAUTH_2, null, null, $description, null, null);

    }

    #[Pure]
    final public static function http(string  $securityTitle, string $scheme, ?string $description,
                                      ?string $bearerFormat = null
    ): self
    {

        return new self($securityTitle, self::TYPE_HTTP, null, null, $description, $scheme, $bearerFormat);
    }

    #[Pure]
    final public static function openIdConnect(string $securityTitle, string $openIdConnectUrl, ?string $description
    ): self
    {

        return new self(
            $securityTitle, self::TYPE_OPEN_ID_CONNECT, null, null, $description, null, null, $openIdConnectUrl
        );

    }

    #[Pure]
    final public function getType(): string
    {

        return $this->type;
    }

    #[Pure]
    final public function getSecurityTitle(): string
    {

        return $this->securityTitle;
    }

    public function withFlows(OAuthFlow ...$flows): self
    {

        if ($this->type !== self::TYPE_OAUTH_2) {
            throw new InvalidArgumentException('Only \'oauth2\' can be passed flow types.');
        }

        foreach ($flows as $authFlow) {

            $flow = [
                'scopes' => $authFlow->getScopes() ?: (object)[],
            ];

            if ($authFlow->getRefreshUrl()) {
                $flow['refreshUrl'] = $authFlow->getRefreshUrl();
            }

            switch ($authFlow->getType()) {

                case OAuthFlow::TYPE_AUTHORIZATION_CODE:

                    $flow['tokenUrl'] = $authFlow->getTokenUrl();
                    $flow['authorizationUrl'] = $authFlow->getAuthorizationUrl();

                    $this->flows['authorizationCode'] = $flow;

                    break;

                case OAuthFlow::TYPE_IMPLICIT:

                    $flow['authorizationUrl'] = $authFlow->getAuthorizationUrl();

                    $this->flows['implicit'] = $flow;
                    break;

                case OAuthFlow::TYPE_PASSWORD:

                    $flow['tokenUrl'] = $authFlow->getTokenUrl();

                    $this->flows['password'] = $flow;
                    break;

                case OAuthFlow::TYPE_CLIENT_CREDENTIALS:

                    $flow['tokenUrl'] = $authFlow->getTokenUrl();

                    $this->flows['clientCredentials'] = $flow;
                    break;

            }

        }

        return $this;

    }

    public function jsonSerialize(): array
    {

        $json = [
            'type' => $this->type
        ];

        if ($this->description !== null) {
            $json['description'] = $this->description;
        }

        if ($this->type === self::TYPE_API_KEY) {

            $json['in'] = $this->in;
            $json['name'] = $this->name;

        } elseif ($this->type === self::TYPE_OAUTH_2) {

            $json['flows'] = $this->flows;

        } elseif ($this->type === self::TYPE_HTTP) {

            $json['scheme'] = $this->scheme;

            if ($this->bearerFormat !== null) {
                $json['bearerFormat'] = $this->bearerFormat;
            }

        } elseif ($this->type === self::TYPE_OPEN_ID_CONNECT) {

            $json['openIdConnectUrl'] = $this->openIdConnectUrl;

        }

        return $json;
    }
}