<?php

declare(strict_types=1);

namespace ITB\CmlifeClient\Exception;

use JsonException;
use RuntimeException;

final class MyStudiesRetrievalFailedException extends RuntimeException
{
    public const CONNECTION_ERROR_CODE = 1;
    public const JSON_DECODE_ERROR_CODE = 2;

    /**
     * @param JsonException $exception
     * @return MyStudiesRetrievalFailedException
     */
    public static function createWithJsonDecodeException(JsonException $exception): MyStudiesRetrievalFailedException
    {
        return new self(
            'My studies could not be retrieved from cmlife, because the returned data contains invalid JSON.',
            self::JSON_DECODE_ERROR_CODE,
            $exception
        );
    }
}
