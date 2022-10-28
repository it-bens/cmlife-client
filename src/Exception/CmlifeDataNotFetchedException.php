<?php

declare(strict_types=1);

namespace ITB\CmlifeClient\Exception;

use RuntimeException;

final class CmlifeDataNotFetchedException extends RuntimeException
{
    /**
     * @return CmlifeDataNotFetchedException
     */
    public static function create(): CmlifeDataNotFetchedException
    {
        return new self(
            'The clients methods can only be used after the required data is fetched from cmlife. This can be done by calling the \'fetchDataFromCmlife()\' method.'
        );
    }
}
