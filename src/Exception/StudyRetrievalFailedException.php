<?php

declare(strict_types=1);

namespace ITB\CmlifeClient\Exception;

use JsonException;
use RuntimeException;

final class StudyRetrievalFailedException extends RuntimeException
{
    public const CONNECTION_ERROR_CODE = 1;
    public const JSON_DECODE_ERROR_CODE = 2;

    /**
     * @param int $statusCode
     * @return StudyRetrievalFailedException
     */
    public static function createWithHttpStatusCode(int $statusCode): StudyRetrievalFailedException
    {
        return new self(
            sprintf('The studies of you could not be retrieved from cmlife. The status code was %d.', $statusCode),
            self::CONNECTION_ERROR_CODE
        );
    }

    /**
     * @param JsonException $exception
     * @return StudyRetrievalFailedException
     */
    public static function createWithJsonDecodeException(JsonException $exception): StudyRetrievalFailedException
    {
        return new self(
            'The study could not be retrieved from cmlife, because the returned data contains invalid JSON.',
            self::JSON_DECODE_ERROR_CODE,
            $exception
        );
    }
}
