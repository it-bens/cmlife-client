<?php

declare(strict_types=1);

namespace ITB\CmlifeClient\Storage\Exception;

use Exception;
use ITB\CmlifeClient\Exception\StorageException;
use RuntimeException;

final class CourseSearchWithLinkNodesAndSemesterFailedException extends RuntimeException implements StorageException
{
    /**
     * @param Exception $exception
     * @return CourseSearchWithLinkNodesAndSemesterFailedException
     */
    public static function create(Exception $exception): CourseSearchWithLinkNodesAndSemesterFailedException
    {
        return new self('The course search with a list of link nodes for a specific semester failed with an exception.', previous: $exception);
    }
}
