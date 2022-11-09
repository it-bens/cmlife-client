<?php

declare(strict_types=1);

namespace ITB\CmlifeClient\Storage;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\Tools\SchemaTool;
use Exception;
use ITB\CmlifeClient\Exception\StorageException;
use ITB\CmlifeClient\Model\Course;
use ITB\CmlifeClient\Model\Curriculum;
use ITB\CmlifeClient\Model\Curriculum\Node;
use ITB\CmlifeClient\Model\Person;
use ITB\CmlifeClient\Model\Semester;
use ITB\CmlifeClient\Model\Study;
use ITB\CmlifeClient\Storage\Exception\OrmSchemaCreationFailedException;
use ITB\CmlifeClient\Storage\Exception\OrmSetupFailedException;
use ITB\CmlifeClient\Storage\Repository\CourseRepository;
use ITB\CmlifeClient\Storage\Repository\NodeRepository;
use ITB\CmlifeClient\Storage\Repository\PersonRepository;
use ITB\CmlifeClient\Storage\Repository\SemesterRepository;
use ITB\CmlifeClient\Storage\Repository\StudyRepository;

final class DataStorage implements DataStorageInterface
{
    private readonly EntityManagerInterface $entityManager;

    private readonly SemesterRepository $semesterRepository;
    private readonly PersonRepository $personRepository;
    private readonly CourseRepository $courseRepository;
    private readonly StudyRepository $studyRepository;
    private readonly NodeRepository $nodeRepository;

    /**
     * @param array<string, mixed> $databaseConnectionParameters
     * @throws StorageException
     */
    public function __construct(array $databaseConnectionParameters)
    {
        try {
            $config = ORMSetup::createAttributeMetadataConfiguration(paths: [__DIR__ . '/../Model'], isDevMode: false);
            $this->entityManager = EntityManager::create($databaseConnectionParameters, $config);
        } catch (ORMException $exception) {
            throw OrmSetupFailedException::create($exception);
        }

        try {
            // updateSchema() keeps any existing tables.
            $dbSchemaTool = new SchemaTool($this->entityManager);
            $dbSchemaTool->updateSchema([
                $this->entityManager->getClassMetadata(Semester::class),
                $this->entityManager->getClassMetadata(Person::class),
                $this->entityManager->getClassMetadata(Course::class),
                $this->entityManager->getClassMetadata(Study::class),
                $this->entityManager->getClassMetadata(Curriculum::class),
                $this->entityManager->getClassMetadata(Node::class),
            ]);
        } catch (Exception $exception) {
            throw OrmSchemaCreationFailedException::create($exception);
        }

        $this->semesterRepository = new SemesterRepository($this->entityManager);
        $this->personRepository = new PersonRepository($this->entityManager);
        $this->courseRepository = new CourseRepository($this->entityManager);
        $this->studyRepository = new StudyRepository($this->entityManager);
        $this->nodeRepository = new NodeRepository($this->entityManager);
    }

    /**
     * @return DataStorage
     * @throws StorageException
     */
    public static function createWithInMemorySqliteDatabase(): DataStorage
    {
        $databaseConnectionParameters = ['driver' => 'pdo_sqlite', 'memory' => true];

        return new self($databaseConnectionParameters);
    }

    /**
     * @return CourseRepository
     */
    public function getCourseRepository(): CourseRepository
    {
        return $this->courseRepository;
    }

    /**
     * @return EntityManagerInterface
     */
    public function getEntityManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }

    /**
     * @return NodeRepository
     */
    public function getNodeRepository(): NodeRepository
    {
        return $this->nodeRepository;
    }

    /**
     * @return PersonRepository
     */
    public function getPersonRepository(): PersonRepository
    {
        return $this->personRepository;
    }

    /**
     * @return SemesterRepository
     */
    public function getSemesterRepository(): SemesterRepository
    {
        return $this->semesterRepository;
    }

    /**
     * @return StudyRepository
     */
    public function getStudyRepository(): StudyRepository
    {
        return $this->studyRepository;
    }

    /**
     * @param Course $course
     * @return void
     * @throws ORMException
     */
    public function persistCourse(Course $course): void
    {
        $persistedCourse = $this->courseRepository->find($course->getId());
        if (null !== $persistedCourse) {
            $this->entityManager->remove($persistedCourse);
        }

        $this->entityManager->persist($course);
    }

    /**
     * @param Person $person
     * @return void
     * @throws ORMException
     */
    public function persistPerson(Person $person): void
    {
        $persistedPerson = $this->personRepository->find($person->getUri());
        if (null !== $persistedPerson) {
            $this->entityManager->remove($persistedPerson);
        }

        $this->entityManager->persist($person);
    }

    /**
     * @param Semester $semester
     * @return void
     * @throws ORMException
     */
    public function persistSemester(Semester $semester): void
    {
        $persistedSemester = $this->semesterRepository->find($semester->getId());
        if (null !== $persistedSemester) {
            $this->entityManager->remove($persistedSemester);
        }

        $this->entityManager->persist($semester);
    }

    /**
     * @param Study $study
     * @return void
     * @throws ORMException
     */
    public function persistStudy(Study $study): void
    {
        $persistedStudy = $this->studyRepository->find($study->getId());
        if (null !== $persistedStudy) {
            $this->entityManager->remove($study);
        }

        $this->entityManager->persist($study);
    }
}
