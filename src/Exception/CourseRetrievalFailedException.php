<?php

declare(strict_types=1);

namespace ITB\CmlifeClient\Exception;

use JsonException;
use RuntimeException;

final class CourseRetrievalFailedException extends RuntimeException
{
    public const CONNECTION_ERROR_CODE = 1;
    public const JSON_DECODE_ERROR_CODE = 2;

    /**
     * @param int $statusCode
     * @return CourseRetrievalFailedException
     */
    public static function createWithHttpStatusCode(int $statusCode): CourseRetrievalFailedException
    {
        return new self(sprintf('The courses could not be retrieved from cmlife. The status code was %d.', $statusCode), self::CONNECTION_ERROR_CODE);
    }

    /**
     * @param JsonException $exception
     * @return CourseRetrievalFailedException
     */
    public static function createWithJsonDecodeException(JsonException $exception): CourseRetrievalFailedException
    {
        return new self(
            'The courses could not be retrieved from cmlife, because the returned data contains invalid JSON.',
            self::JSON_DECODE_ERROR_CODE,
            $exception
        );
    }
}
