<?php


namespace nickdnk\OpenAPI\Components;

use InvalidArgumentException;
use JsonSerializable;
use nickdnk\OpenAPI\Components\Security\RequiredSecurityScheme;
use nickdnk\OpenAPI\OpenAPIDocument;
use nickdnk\OpenAPI\Types\Base;

class Endpoint implements JsonSerializable
{

    const GET    = 'get';
    const POST   = 'post';
    const PUT    = 'put';
    const DELETE = 'delete';
    const PATCH  = 'patch';

    const CONTENT_TYPE_APPLICATION_JSON                  = 'application/json';
    const CONTENT_TYPE_APPLICATION_X_WWW_FORM_URLENCODED = 'application/x-www-form-urlencoded';

    private $tag, $summary, $description, $responses;
    /**
     * @var array|null
     */
    private $requestBodies;
    /**
     * @var array|null
     */
    private $parameters;
    /**
     * @var string
     */
    private $httpMethod;
    /**
     * @var bool
     */
    private $deprecated;

    /**
     * @var string[]|null
     */
    private $requiredSecuritySchemes;

    /**
     * Endpoint constructor.
     *
     * @param string $httpMethod
     * @param string $summary
     * @param string $description
     * @param Response[] $responses
     * @param Parameter[]|null $parameters
     */
    private function __construct(string $httpMethod, string $summary, string $description, array $responses,
                                 ?array $parameters = null
    )
    {

        $this->tag = null; // set by section on output.
        $this->summary = $summary;
        $this->description = $description;
        $this->responses = $responses;
        $this->parameters = $parameters;
        $this->httpMethod = $httpMethod;
        $this->deprecated = false;

    }

    final public static function get(string $andSummary, string $description): self
    {

        return new self(
            self::GET, $andSummary, $description, []
        );
    }

    final public static function post(string $andSummary, string $description): self
    {

        return new self(
            self::POST, $andSummary, $description, []
        );
    }

    final public static function put(string $andSummary, string $description): self
    {

        return new self(
            self::PUT, $andSummary, $description, []
        );
    }

    final public static function delete(string $andSummary, string $description): self
    {

        return new self(
            self::DELETE, $andSummary, $description, []
        );
    }

    final public static function patch(string $andSummary, string $description): self
    {

        return new self(
            self::PATCH, $andSummary, $description, []
        );
    }

    public function deprecated(): self
    {

        $this->deprecated = true;

        return $this;
    }

    public function withResponses(Response ...$response): self
    {

        $this->responses = $response;

        return $this;

    }

    public function withParameters(Parameter ...$parameter): self
    {

        $this->parameters = $parameter;

        return $this;

    }

    /**
     * Adds a requirement for a SecurityScheme to an endpoint. If this method is called multiple times for the same
     * endpoint, the schemes will be added as OR requirements. To add multiple schemes to be validated as AND, pass
     * an array of requirements and call the method once instead.
     *
     * To explicitly remove all requirements from an endpoint simply pass an empty array to this method.
     *
     * @param RequiredSecurityScheme[]|RequiredSecurityScheme
     *
     * @return Endpoint
     */
    public function withRequiredSecuritySchemes($securityScheme): self
    {

        if ($this->requiredSecuritySchemes === null) {
            $this->requiredSecuritySchemes = [];
        }

        $this->requiredSecuritySchemes[] = $securityScheme;

        return $this;

    }

    public function setTag(string $tag): void
    {

        $this->tag = $tag;
    }

    public function withRequestBodyFromDocument(Base $class, string $contentType = self::CONTENT_TYPE_APPLICATION_JSON): self
    {

        if ($this->httpMethod === Endpoint::GET) {
            throw new InvalidArgumentException('Passed request body entity ' . (is_string($class) ? ($class . ' ') : '') . 'to GET endpoint.');
        }

        if ($this->requestBodies === null) {
            $this->requestBodies = [];
        }

        if (!isset($this->requestBodies[$contentType])) {
            $this->requestBodies[$contentType] = [];
        }

        $this->requestBodies[$contentType][] = $class;

        return $this;

    }

    public function withRequestBodyFromEntity(string $class, string $contentType = self::CONTENT_TYPE_APPLICATION_JSON): self
    {

        /**
         * @var $class OpenAPIDocument
         */
        return $this->withRequestBodyFromDocument(
            $class::getOpenAPISpec($this->httpMethod),
            $contentType
        );

    }

    final public function getHttpMethod(): string
    {

        return $this->httpMethod;
    }

    /**
     * @return Response[]
     */
    final public function getResponses(): array
    {

        return $this->responses;
    }

    public function jsonSerialize(): array
    {

        $return = [
            'tags'        => [$this->tag],
            'summary'     => $this->summary,
            'operationId' => strtolower(
                    str_replace(
                        ' ',
                        '',
                        $this->tag
                    )
                ) . '-' . lcfirst(
                    ucwords(
                        preg_replace(
                            '/[^[A-Za-z]+/',
                            '',
                            $this->summary
                        )
                    )
                ),
            'description' => $this->description,
        ];

        if ($this->deprecated) {
            $return['deprecated'] = true;
        }

        if ($this->requiredSecuritySchemes !== null) {

            $return['security'] = [];

            foreach ($this->requiredSecuritySchemes as $value) {

                if (is_array($value)) {

                    if (count($value) === 0) {
                        // When adding an empty array for "no security", we don't want to add an empty array.
                        continue;
                    }

                    $toAdd = [];

                    foreach ($value as $scheme) {

                        /** @var RequiredSecurityScheme $scheme */
                        $toAdd[$scheme->getTitle()] = $scheme->getScopes() !== null ? [$scheme->getScopes()] : [];

                    }

                    $return['security'][] = $toAdd;

                } else {

                    /** @var RequiredSecurityScheme $value */
                    $return['security'][] = $value->getScopes() !== null
                        ? [
                            $value->getTitle() => $value->getScopes()
                        ]
                        : [
                            $value->getTitle() => []
                        ];

                }

            }

        }

        /**
         * As some endpoints have more than one response with the same HTTP code, we'll have to loop the responses and map out all the HTTP
         * codes used. Once that's done we can decide if the key (HTTP code) occurs more than once and should have an array of values
         * (responses) or just a single response.
         */
        $responseKeys = [];

        foreach ($this->responses as $response) {

            $responseKeys[$response->getHttpCode()][] = $response;

        }

        foreach ($responseKeys as $httpCode => $responses) {

            /** @var Response[] $responses */

            $objectInHttpCode = [
                'description' => Response::mapHttpCode($httpCode),
            ];

            $schemas = [];

            foreach ($responses as $response) {

                if ($response->getSchema() !== null) {

                    $schemas[] = $response->getSchema();

                }

                if ($response->getHeaders() !== null) {

                    $objectInHttpCode['headers'] = [];

                    foreach ($response->getHeaders() as $header) {

                        /** @var Header $header */
                        $objectInHttpCode['headers'][$header->getHeader()] = $header;

                    }

                }
            }

            if (count($schemas) > 0) {

                $objectInHttpCode['content'] = [
                    'application/json' => [
                        'schema' => count($schemas) > 1 ? ['oneOf' => $schemas] : $schemas[0]
                    ]
                ];

            }

            $return['responses'][(string)$httpCode] = $objectInHttpCode;

        }

        if ($this->requestBodies) {

            $return['requestBody'] = [
                'required' => true,
                'content'  => []
            ];

            $contentTypes = [];
            foreach ($this->requestBodies as $contentType => $requestBodies) {
                if (!isset($contentTypes[$contentType])) {
                    $contentTypes[$contentType] = [];
                }
                foreach ($requestBodies as $requestBody) {
                    $contentTypes[$contentType][] = $requestBody;
                }
            }

            foreach ($contentTypes as $contentType => $bodies) {

                $return['requestBody']['content'][$contentType] = [
                    'schema' => count($bodies) > 1 ? [
                        'oneOf' => $bodies
                    ] : $bodies[0]
                ];

            }

        }

        if ($this->parameters) {

            $return['parameters'] = $this->parameters;

        }

        return $return;

    }
}
