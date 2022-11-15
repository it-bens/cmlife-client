<?php

declare(strict_types=1);

namespace ITB\CmlifeClient\Tests;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\Tools\SchemaTool;
use ITB\CmlifeClient\Model\Course;
use ITB\CmlifeClient\Model\Curriculum;
use ITB\CmlifeClient\Model\Curriculum\Node;
use ITB\CmlifeClient\Model\Person;
use ITB\CmlifeClient\Model\Semester;
use ITB\CmlifeClient\Model\Study;
use ITB\CmlifeClient\Storage\DoctrineDataStorage;

trait CreateDoctrineDataStorageTrait
{
    /**
     * @param array<string, mixed> $databaseConnectionParameters
     * @return DoctrineDataStorage
     * @throws ORMException
     */
    private static function createDoctrineDataStorage(array $databaseConnectionParameters = ['driver' => 'pdo_sqlite', 'memory' => true]): DoctrineDataStorage
    {
        $config = ORMSetup::createAttributeMetadataConfiguration(paths: [__DIR__ . '/src/Model'], isDevMode: false);
        $entityManager = EntityManager::create($databaseConnectionParameters, $config);

        $dbSchemaTool = new SchemaTool($entityManager);
        $dbSchemaTool->createSchema([
            $entityManager->getClassMetadata(Semester::class),
            $entityManager->getClassMetadata(Person::class),
            $entityManager->getClassMetadata(Course::class),
            $entityManager->getClassMetadata(Study::class),
            $entityManager->getClassMetadata(Curriculum::class),
            $entityManager->getClassMetadata(Node::class),
        ]);

        return new DoctrineDataStorage($entityManager);
    }
}
