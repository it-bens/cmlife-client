<?php

declare(strict_types=1);

namespace ITB\CmlifeClient\Connection;

use JsonException;

final class RequestData
{
    public readonly string $uri;

    /**
     * @param string $method
     * @param string $uri
     * @param string|null $query
     * @param string|null $body
     */
    public function __construct(
        public readonly string $method,
        string $uri,
        public readonly ?string $query = null,
        public readonly ?string $body = null
    ) {
        $this->uri = (null !== $this->query) ? $uri . '?' . $this->query : $uri;
    }

    /**
     * @param string $method
     * @param string $uri
     * @param array<string, string|int> $queryParameters
     * @param array<string|int, mixed> $json
     * @return RequestData
     * @throws JsonException
     */
    public static function create(string $method, string $uri, array $queryParameters = [], array $json = []): RequestData
    {
        $query = (0 !== count($queryParameters)) ? http_build_query($queryParameters) : null;
        $body = (0 !== count($json)) ? json_encode($json, flags: JSON_THROW_ON_ERROR) : null;

        return new self($method, $uri, $query, $body);
    }
}
