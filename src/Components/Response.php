<?php


namespace nickdnk\OpenAPI\Components;

use InvalidArgumentException;
use nickdnk\OpenAPI\Types\AString;
use nickdnk\OpenAPI\Types\Base;

class Response
{

    private $httpCode, $schema, $headers;


    /**
     * Response constructor.
     *
     * @param int       $httpCode
     * @param Base|null $data
     */
    private function __construct(int $httpCode, ?Base $data)
    {

        $this->httpCode = $httpCode;
        $this->schema = $data;
    }

    final public static function mapHttpCode(int $code)
    {

        switch ($code) {

            case 200:
                return 'OK';
            case 201:
                return 'Created';
            case 202:
                return 'Accepted';
            case 203:
                return 'Non-Authoritative Information';
            case 204:
                return 'No Content';
            case 206:
                return 'Partial Content';
            case 226:
                return 'IM Used';
            case 300:
                return 'Multiple Choices';
            case 301:
                return 'Moved Permanently';
            case 302:
                return 'Found';
            case 303:
                return 'See Other';
            case 304:
                return 'Not Modified';
            case 305:
                return 'Use Proxy';
            case 307:
                return 'Temporary Redirect';
            case 400:
                return 'Bad Request';
            case 401:
                return 'Unauthorized';
            case 402:
                return 'Payment Required';
            case 403:
                return 'Forbidden';
            case 404:
                return 'Not Found';
            case 405:
                return 'Method Not Allowed';
            case 406:
                return 'Not Acceptable';
            case 407:
                return 'Proxy Authentication Required';
            case 408:
                return 'Request Timeout';
            case 409:
                return 'Conflict';
            case 410:
                return 'Gone';
            case 411:
                return 'Length Required';
            case 412:
                return 'Precondition Failed';
            case 413:
                return 'Request Entity Too Large';
            case 414:
                return 'Request-URI Too Long';
            case 415:
                return 'Unsupported Media Type';
            case 416:
                return 'Requested Range Not Satisfiable';
            case 417:
                return 'Expectation Failed';
            case 422:
                return 'Unprocessable Entity';
            case 423:
                return 'Locked';
            case 424:
                return 'Failed Dependency';
            case 426:
                return 'Upgrade Required';
            case 428:
                return 'Precondition Required';
            case 429:
                return 'Too Many Requests';
            case 431:
                return 'Request Header Fields Too Large';
            case 451:
                return 'Unavailable For Legal Reasons';
            case 500:
                return 'Internal Server Error';
            case 501:
                return 'Not Implemented';
            case 502:
                return 'Bad Gateway';
            case 503:
                return 'Service Unavailable';
            case 504:
                return 'Gateway Timeout';
            case 505:
                return 'HTTP Version Not Supported';
            case 507:
                return 'Insufficient Storage';
            case 508:
                return 'Loop Detected';
            case 510:
                return 'Not Extended';
            case 511:
                return 'Network Authentication Required';
            default:
                throw new InvalidArgumentException(
                    'Invalid or unknown HTTP status (' . $code . ') passed to map function.'
                );
        }

    }

    final public function withHeader(Header $header)
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
    final public function getHeaders(): ?array
    {

        return $this->headers;
    }

    /**
     * @return Base|null
     */
    final public function getSchema(): ?Base
    {

        return $this->schema;
    }

    /**
     * @return mixed
     */
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

        $self = new self(
            $httpCode, null
        );

        return $self->withHeader(
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