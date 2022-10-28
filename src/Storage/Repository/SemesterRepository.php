<?php

declare(strict_types=1);

namespace ITB\CmlifeClient\Storage\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use ITB\CmlifeClient\Exception\StorageException;
use ITB\CmlifeClient\Model\Semester;
use ITB\CmlifeClient\Storage\Exception\MoreThanOneSemesterIsCurrentException;
use ITB\CmlifeClient\Storage\Exception\NoSemesterIsCurrentException;

final class SemesterRepository
{
    /** @var EntityRepository<Semester> */
    private readonly EntityRepository $entityRepository;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityRepository = $entityManager->getRepository(Semester::class);
    }

    /**
     * @param int $semesterId
     * @return Semester|null
     */
    public function find(int $semesterId): ?Semester
    {
        return $this->entityRepository->find($semesterId);
    }

    /**
     * @return Semester[]
     */
    public function findAll(): array
    {
        return $this->entityRepository->findAll();
    }

    /**
     * @return Semester
     * @throws StorageException
     */
    public function findCurrent(): Semester
    {
        $currentSemesters = $this->entityRepository->findBy(['isCurrent' => true]);
        if (0 === count($currentSemesters)) {
            throw NoSemesterIsCurrentException::create();
        }
        if (count($currentSemesters) > 1) {
            throw MoreThanOneSemesterIsCurrentException::create();
        }

        return $currentSemesters[0];
    }
}
