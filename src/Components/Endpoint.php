<?php


namespace nickdnk\OpenAPI\Components;

use InvalidArgumentException;
use JsonSerializable;
use nickdnk\OpenAPI\Components\Security\RequiredSecurityScheme;
use nickdnk\OpenAPI\OpenAPIDocument;

class Endpoint implements JsonSerializable
{

    const GET    = 'get';
    const POST   = 'post';
    const PUT    = 'put';
    const DELETE = 'delete';
    const PATCH  = 'patch';

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
     * @param string           $httpMethod
     * @param string           $summary
     * @param string           $description
     * @param Response[]       $responses
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

    public function withResponses(Response ... $response): self
    {

        $this->responses = $response;

        return $this;

    }

    public function withParameters(Parameter ... $parameter): self
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

    /**
     * @param string $tag
     */
    public function setTag(string $tag): void
    {

        $this->tag = $tag;
    }

    public function withRequestBodyFromEntity($class): self
    {

        if ($this->httpMethod === Endpoint::GET) {
            throw new InvalidArgumentException('Passed request body entity ' . $class . ' to GET endpoint.');
        }

        /**
         * @var OpenAPIDocument $class
         */

        if ($this->requestBodies === null) {
            $this->requestBodies = [];
        }

        $this->requestBodies[] = $class::getOpenAPISpec(
            $this->httpMethod
        );

        return $this;

    }

    /**
     * @return string
     */
    final public function getHttpMethod(): string
    {

        return $this->httpMethod;
    }

    /**
     * @return array|Response[]
     */
    final public function getResponses()
    {

        return $this->responses;
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

            if ($responses[0]->getSchema() !== null) {

                if (count($responses) > 1) {

                    $schemas = [];
                    foreach ($responses as $response) {
                        $schemas[] = $response->getSchema();
                    }

                    $applicationJson = [
                        'schema' => ['oneOf' => $schemas]
                    ];

                } else {

                    $applicationJson = [
                        'schema' => $responses[0]->getSchema()
                    ];

                }

                $objectInHttpCode['content'] = [
                    'application/json' => $applicationJson
                ];

            }

            foreach ($responses as $response) {

                if ($response->getHeaders() !== null) {

                    $objectInHttpCode['headers'] = [];

                    foreach ($response->getHeaders() as $header) {

                        /** @var Header $header */
                        $objectInHttpCode['headers'][$header->getHeader()] = $header;

                    }

                }

            }

            $return['responses'][(string)$httpCode] = $objectInHttpCode;

        }

        if ($this->requestBodies) {

            $return['requestBody'] = [
                'required' => true,
                'content'  => [
                    'application/json' => [
                        'schema' => count($this->requestBodies) > 1 ? [
                            'oneOf' => $this->requestBodies
                        ] : $this->requestBodies[0]
                    ]
                ]
            ];

        }

        if ($this->parameters) {

            $return['parameters'] = $this->parameters;

        }

        return $return;

    }
}