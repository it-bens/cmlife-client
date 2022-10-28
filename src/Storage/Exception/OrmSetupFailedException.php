<?php

declare(strict_types=1);

namespace ITB\CmlifeClient\Storage\Exception;

use Doctrine\ORM\Exception\ORMException;
use ITB\CmlifeClient\Exception\StorageException;
use RuntimeException;

final class OrmSetupFailedException extends RuntimeException implements StorageException
{
    /**
     * @param ORMException $exception
     * @return OrmSetupFailedException
     */
    public static function create(ORMException $exception): OrmSetupFailedException
    {
        return new self('The doctrine ORM setup (entity manager and repository creation) failed with an exception.', previous: $exception);
    }
}
