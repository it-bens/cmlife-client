<?php

declare(strict_types=1);

namespace ITB\CmlifeClient\Storage\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use ITB\CmlifeClient\Exception\StorageException;
use ITB\CmlifeClient\Model\Person;
use ITB\CmlifeClient\Storage\Exception\MoreThanOnePersonIsMeException;
use ITB\CmlifeClient\Storage\Exception\NoPersonIsMeException;

final class PersonRepository
{
    /** @var EntityRepository<Person> */
    private readonly EntityRepository $entityRepository;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityRepository = $entityManager->getRepository(Person::class);
    }

    /**
     * @return Person
     * @throws StorageException
     */
    public function findMe(): Person
    {
        $persons = $this->entityRepository->findBy(['isMe' => true]);
        if (0 === count($persons)) {
            throw NoPersonIsMeException::create();
        }
        if (count($persons) > 1) {
            throw MoreThanOnePersonIsMeException::create();
        }

        return $persons[0];
    }
}
