<?php


namespace nickdnk\OpenAPI\Components;

use InvalidArgumentException;
use JetBrains\PhpStorm\Pure;
use nickdnk\OpenAPI\Types\AString;
use nickdnk\OpenAPI\Types\Base;

class Response
{

    private ?array $headers;
    private ?Base $schema;
    private int $httpCode;

    #[Pure]
    private function __construct(int $httpCode, ?Base $data)
    {

        $this->httpCode = $httpCode;
        $this->schema = $data;
        $this->headers = null;
    }

    final public static function mapHttpCode(int $code): string
    {

        return match ($code) {
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            203 => 'Non-Authoritative Information',
            204 => 'No Content',
            206 => 'Partial Content',
            226 => 'IM Used',
            300 => 'Multiple Choices',
            301 => 'Moved Permanently',
            302 => 'Found',
            303 => 'See Other',
            304 => 'Not Modified',
            305 => 'Use Proxy',
            307 => 'Temporary Redirect',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            406 => 'Not Acceptable',
            407 => 'Proxy Authentication Required',
            408 => 'Request Timeout',
            409 => 'Conflict',
            410 => 'Gone',
            411 => 'Length Required',
            412 => 'Precondition Failed',
            413 => 'Request Entity Too Large',
            414 => 'Request-URI Too Long',
            415 => 'Unsupported Media Type',
            416 => 'Requested Range Not Satisfiable',
            417 => 'Expectation Failed',
            422 => 'Unprocessable Entity',
            423 => 'Locked',
            424 => 'Failed Dependency',
            426 => 'Upgrade Required',
            428 => 'Precondition Required',
            429 => 'Too Many Requests',
            431 => 'Request Header Fields Too Large',
            451 => 'Unavailable For Legal Reasons',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Timeout',
            505 => 'HTTP Version Not Supported',
            507 => 'Insufficient Storage',
            508 => 'Loop Detected',
            510 => 'Not Extended',
            511 => 'Network Authentication Required',
            default => throw new InvalidArgumentException(
                'Invalid or unknown HTTP status (' . $code . ') passed to map function.'
            ),
        };

    }

    final public function withHeader(Header $header): self
    {

        if ($this->headers === null) {
            $this->headers = [];
        }

        $this->headers[] = $header;

        return $this;
    }

    /**
     * @return Header[]|null
     */
    #[Pure]
    final public function getHeaders(): ?array
    {

        return $this->headers;
    }

    #[Pure]
    final public function getSchema(): ?Base
    {

        return $this->schema;
    }

    #[Pure]
    final public function getHttpCode(): int
    {

        return $this->httpCode;
    }

    final public static function success(?Base $data = null, ?int $httpCode = null): Response
    {

        if ($httpCode !== null && ($httpCode < 100 || $httpCode >= 300)) {
            throw new InvalidArgumentException('Invalid HTTP code passed to success: ' . $httpCode);
        }

        return new self(
            $data === null ? ($httpCode === null ? 204 : $httpCode) : ($httpCode === null ? 200 : $httpCode), $data
        );

    }

    final public static function redirect(string $description, ?string $example = null, int $httpCode = 303): Response
    {

        if ($httpCode !== null && ($httpCode < 300 || $httpCode >= 400)) {
            throw new InvalidArgumentException('Invalid HTTP code passed to redirect: ' . $httpCode);
        }

        return (new self(
            $httpCode, null
        ))->withHeader(
            new Header(
                Header::LOCATION,
                $description,
                AString::get()
                    ->withFormat('url')
                    ->withExample($example)
            )
        );

    }

    final public static function error(?Base $data = null, int $httpCode = null): Response
    {

        if ($httpCode !== null && ($httpCode < 400 || $httpCode >= 600)) {
            throw new InvalidArgumentException('Invalid HTTP code passed to error: ' . $httpCode);
        }

        return new self(
            $httpCode === null ? 400 : $httpCode, $data
        );

    }

}