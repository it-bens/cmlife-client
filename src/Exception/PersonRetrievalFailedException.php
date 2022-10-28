<?php

declare(strict_types=1);

namespace ITB\CmlifeClient\Exception;

use JsonException;
use RuntimeException;

final class PersonRetrievalFailedException extends RuntimeException
{
    public const CONNECTION_ERROR_CODE = 1;
    public const JSON_DECODE_ERROR_CODE = 2;

    /**
     * @param int $statusCode
     * @return PersonRetrievalFailedException
     */
    public static function createWithHttpStatusCode(int $statusCode): PersonRetrievalFailedException
    {
        return new self(
            sprintf('The current person (you) could not be retrieved from cmlife. The status code was %d.', $statusCode),
            self::CONNECTION_ERROR_CODE
        );
    }

    /**
     * @param JsonException $exception
     * @return PersonRetrievalFailedException
     */
    public static function createWithJsonDecodeException(JsonException $exception): PersonRetrievalFailedException
    {
        return new self(
            'The person could not be retrieved from cmlife, because the returned data contains invalid JSON.',
            self::JSON_DECODE_ERROR_CODE,
            $exception
        );
    }
}
