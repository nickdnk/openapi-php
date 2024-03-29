<?php


namespace nickdnk\OpenAPI\Components;

use InvalidArgumentException;
use JetBrains\PhpStorm\Pure;
use JsonSerializable;
use nickdnk\OpenAPI\Components\Security\RequiredSecurityScheme;
use nickdnk\OpenAPI\Components\Security\SecurityScheme;

class OpenAPI implements JsonSerializable
{

    private string $openApi;
    private Info $info;

    /** @var Server[] $servers */
    private array $servers;

    /** @var Section[] $sections */
    private array $sections;

    /** @var ExternalDocs[] $externalDocs */
    private ?array $externalDocs;

    /** @var SecurityScheme[] $security */
    private ?array $security;

    /** @var RequiredSecurityScheme[] $requiredSecuritySchemes */
    private ?array $requiredSecuritySchemes;

    /**
     * @param string $openApi
     * @param Info $info
     * @param Server[] $servers
     * @param SecurityScheme[]|null $security
     * @param RequiredSecurityScheme[]|null $requiredSecuritySchemes
     * @param ExternalDocs[]|null $externalDocs
     */
    #[Pure]
    public function __construct(string $openApi, Info $info, array $servers, ?array $security = null, ?array $requiredSecuritySchemes = null, ?array $externalDocs = null)
    {

        $this->openApi = $openApi;
        $this->info = $info;
        $this->sections = [];
        $this->servers = $servers;
        $this->security = $security;
        $this->requiredSecuritySchemes = $requiredSecuritySchemes;
        $this->externalDocs = $externalDocs;
    }

    final public function addExternalDocs(ExternalDocs $docs): void
    {

        if ($this->externalDocs !== null) {
            $this->externalDocs = [];
        }

        $this->externalDocs[] = $docs;
    }

    final public function addSection(Section $section): void
    {

        $this->sections[] = $section;
    }

    /**
     * Adds a requirement for a SecurityScheme to all endpoints. If this method is called multiple times, the schemes
     * will be added as OR requirements. To add multiple schemes to be validated as AND, pass an array of requirements
     * and call the method once instead.
     *
     * The schemas must reference security component objects that were added to the root constructor.
     *
     * This logic is similar to the logic provided for Endpoints, with the exception that you cannot pass an empty
     * array to this method (as there is nothing to "override" in that context). Just don't use this method if you don't
     * want security schemas added to all endpoints.
     *
     * @param RequiredSecurityScheme|RequiredSecurityScheme[]
     */
    final public function addRequiredSecuritySchemes($securitySchemes): void
    {

        if (is_array($securitySchemes)) {

            if (count($securitySchemes) === 0) {

                throw new InvalidArgumentException(
                    'You cannot pass an empty required security schema array to the root object.'
                );
            }

        } elseif (!($securitySchemes instanceof RequiredSecurityScheme)) {

            throw new InvalidArgumentException(
                'Invalid argument passed to addRequiredSecuritySchemes.'
                . ' Must be an instance of or an array of RequiredSecurityScheme. Received ' . get_class(
                    $securitySchemes
                )
            );

        }

        $this->requiredSecuritySchemes[] = $securitySchemes;
    }

    public function jsonSerialize(): array
    {

        $return = [
            'openapi' => $this->openApi,
            'servers' => $this->servers,
            'info'    => $this->info,
            'tags'    => [],
        ];

        if ($this->security !== null) {

            $return['components'] = ['securitySchemes' => []];

            foreach ($this->security as $securityComponent) {
                $return['components']['securitySchemes'][$securityComponent->getSecurityTitle()] = $securityComponent;
            }

        }

        if ($this->requiredSecuritySchemes !== null) {

            $return['security'] = [];

            foreach ($this->requiredSecuritySchemes as $value) {

                if (is_array($value)) {

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

        foreach ($this->sections as $section) {

            /** @var Section $section */

            $return['tags'][] = $section;

            foreach ($section->getPaths() as $path) {

                $return['paths'][$path->getPath()] = $path;

                foreach ($path->getEndPoints() as $endPoint) {

                    if (count($endPoint->getResponses()) === 0) {
                        throw new InvalidArgumentException(
                            'Endpoint ' . strtoupper($endPoint->getHttpMethod()) . ' for ' . $path->getPath()
                            . ' has no defined responses.'
                        );
                    }

                    $endPoint->setTag($section->getName());
                }
            }
        }

        return $return;
    }
}