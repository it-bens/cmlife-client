<?php

declare(strict_types=1);

namespace ITB\CmlifeClient\Storage\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use ITB\CmlifeClient\Model\Study;

final class StudyRepository
{
    /** @var EntityRepository<Study> */
    private readonly EntityRepository $entityRepository;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityRepository = $entityManager->getRepository(Study::class);
    }

    /**
     * @return Study[]
     */
    public function findAll(): array
    {
        return $this->entityRepository->findAll();
    }
}
