<?php

declare(strict_types=1);

namespace ITB\CmlifeClient\Exception;

use JsonException;
use RuntimeException;

final class SemesterRetrievalFailedException extends RuntimeException
{
    public const CONNECTION_ERROR_CODE = 1;
    public const JSON_DECODE_ERROR_CODE = 2;

    /**
     * @param int $statusCode
     * @return SemesterRetrievalFailedException
     */
    public static function createWithHttpStatusCode(int $statusCode): SemesterRetrievalFailedException
    {
        return new self(
            sprintf('The current semester could not be retrieved from cmlife. The status code was %d.', $statusCode),
            self::CONNECTION_ERROR_CODE
        );
    }

    /**
     * @param JsonException $exception
     * @return SemesterRetrievalFailedException
     */
    public static function createWithJsonDecodeException(JsonException $exception): SemesterRetrievalFailedException
    {
        return new self(
            'The semester could not be retrieved from cmlife, because the returned data contains invalid JSON.',
            self::JSON_DECODE_ERROR_CODE,
            $exception
        );
    }
}
