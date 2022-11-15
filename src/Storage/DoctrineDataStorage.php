<?php

declare(strict_types=1);

namespace ITB\CmlifeClient\Storage;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use ITB\CmlifeClient\Exception\StorageException;
use ITB\CmlifeClient\Model\Course;
use ITB\CmlifeClient\Model\Curriculum\Node;
use ITB\CmlifeClient\Model\Curriculum\Node\LinkNode;
use ITB\CmlifeClient\Model\Curriculum\NodeInterface;
use ITB\CmlifeClient\Model\Person;
use ITB\CmlifeClient\Model\Semester;
use ITB\CmlifeClient\Model\Study;
use ITB\CmlifeClient\Storage\Exception\MoreThanOnePersonIsMeException;
use ITB\CmlifeClient\Storage\Exception\MoreThanOneSemesterIsCurrentException;
use ITB\CmlifeClient\Storage\Exception\NoPersonIsMeException;
use ITB\CmlifeClient\Storage\Exception\NoSemesterIsCurrentException;

final class DoctrineDataStorage implements DataManagerInterface, DataRepositoryInterface
{
    /** @var EntityRepository<Semester> $semesterRepository */
    private readonly EntityRepository $semesterRepository;
    /** @var EntityRepository<Person> $personRepository */
    private readonly EntityRepository $personRepository;
    /** @var EntityRepository<Course> $courseRepository */
    private readonly EntityRepository $courseRepository;
    /** @var EntityRepository<Study> $studyRepository */
    private readonly EntityRepository $studyRepository;
    /** @var EntityRepository<Node> $nodeRepository */
    private readonly EntityRepository $nodeRepository;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
        $this->semesterRepository = $this->entityManager->getRepository(Semester::class);
        $this->personRepository = $this->entityManager->getRepository(Person::class);
        $this->courseRepository = $this->entityManager->getRepository(Course::class);
        $this->studyRepository = $this->entityManager->getRepository(Study::class);
        $this->nodeRepository = $this->entityManager->getRepository(Node::class);
    }

    /**
     * @return Course[]
     */
    public function findAllCourses(): array
    {
        return $this->courseRepository->findAll();
    }

    /**
     * @return Person[]
     */
    public function findAllPersons(): array
    {
        return $this->personRepository->findAll();
    }

    /**
     * @return Semester[]
     */
    public function findAllSemesters(): array
    {
        return $this->semesterRepository->findAll();
    }

    /**
     * @return Study[]
     */
    public function findAllStudies(): array
    {
        return $this->studyRepository->findAll();
    }

    /**
     * @param int $courseId
     * @return Course|null
     */
    public function findCourse(int $courseId): ?Course
    {
        return $this->courseRepository->find($courseId);
    }

    /**
     * @param LinkNode[] $linkNodes
     * @return Course[]
     */
    public function findCoursesByLinkNodesAndSemester(array $linkNodes, Semester $semester): array
    {
        $courseEquivalenceUris = array_map(static function (LinkNode $node): ?string {
            return $node->getCourseEquivalenceUri();
        }, $linkNodes);
        // Some Link nodes contain no equivalence URI. These null-URIs must be removed.
        $courseEquivalenceUris = array_filter($courseEquivalenceUris);

        $queryBuilder = $this->courseRepository->createQueryBuilder('course')
            ->andWhere($this->entityManager->createQueryBuilder()->expr()->in('course.equivalenceUri', ':equivalenceUris'))
            ->setParameter('equivalenceUris', $courseEquivalenceUris)
            ->andWhere($this->entityManager->createQueryBuilder()->expr()->eq('course.semester', ':semester'))
            ->setParameter('semester', $semester);
        $query = $queryBuilder->getQuery();

        return $query->getResult();
    }

    /**
     * @param Semester $semester
     * @return Course[]
     */
    public function findCoursesBySemester(Semester $semester): array
    {
        return $this->courseRepository->findBy(['semester' => $semester]);
    }

    /**
     * @return Semester
     * @throws StorageException
     */
    public function findCurrentSemester(): Semester
    {
        $currentSemesters = $this->semesterRepository->findBy(['isCurrent' => true]);
        if (0 === count($currentSemesters)) {
            throw NoSemesterIsCurrentException::create();
        }

        return $currentSemesters[0];
    }

    /**
     * @return Person
     * @throws StorageException
     */
    public function findMe(): Person
    {
        $persons = $this->personRepository->findBy(['isMe' => true]);
        if (0 === count($persons)) {
            throw NoPersonIsMeException::create();
        }

        return $persons[0];
    }

    /**
     * @param string $nodeType
     * @param Study $study
     * @return NodeInterface[]
     */
    public function findNodesByTypeAndStudy(string $nodeType, Study $study): array
    {
        $queryBuilder = $this->nodeRepository->createQueryBuilder('node')
            ->andWhere($this->entityManager->createQueryBuilder()->expr()->isInstanceOf('node', ':type'))
            ->setParameter('type', $nodeType);
        $query = $queryBuilder->getQuery();

        $nodes = $query->getResult();

        return array_values(
            array_filter($nodes, static function (NodeInterface $node) use ($study): bool {
                return $node->belongsToRootNode($study->getCurriculum()->getRootNode());
            })
        );
    }

    /**
     * @param string $personUri
     * @return Person|null
     */
    public function findPerson(string $personUri): ?Person
    {
        return $this->personRepository->find($personUri);
    }

    /**
     * @param int $semesterId
     * @return Semester|null
     */
    public function findSemester(int $semesterId): ?Semester
    {
        return $this->semesterRepository->find($semesterId);
    }

    /**
     * @param int $studyId
     * @return Study|null
     */
    public function findStudy(int $studyId): ?Study
    {
        return $this->studyRepository->find($studyId);
    }

    /**
     * @return void
     */
    public function flush(): void
    {
        $this->entityManager->flush();
    }

    /**
     * @param Course $course
     * @return void
     */
    public function persistCourse(Course $course): void
    {
        $persistedCourse = $this->courseRepository->find($course->getId());
        if (null !== $persistedCourse) {
            $persistedCourse->update($course);
            $course = $persistedCourse;
        }

        $this->entityManager->persist($course);
    }

    /**
     * @param Person $person
     * @return void
     */
    public function persistPerson(Person $person): void
    {
        $persistedPerson = $this->personRepository->find($person->getUri());
        if (null !== $persistedPerson) {
            $persistedPerson->update($person);
            $person = $persistedPerson;
        }

        // Only one person can be the current user.
        if ($person->isMe() && 0 !== count($this->personRepository->findBy(['isMe' => true]))) {
            throw MoreThanOnePersonIsMeException::create();
        }

        $this->entityManager->persist($person);
    }

    /**
     * @param Semester $semester
     * @return void
     */
    public function persistSemester(Semester $semester): void
    {
        $persistedSemester = $this->semesterRepository->find($semester->getId());
        if (null !== $persistedSemester) {
            $persistedSemester->update($semester);
            $semester = $persistedSemester;
        }

        // Only one semester can be the current semester.
        if ($semester->isCurrent() && 0 !== count($this->semesterRepository->findBy(['isCurrent' => true]))) {
            throw MoreThanOneSemesterIsCurrentException::create();
        }

        $this->entityManager->persist($semester);
    }

    /**
     * @param Study $study
     * @return void
     */
    public function persistStudy(Study $study): void
    {
        $persistedStudy = $this->studyRepository->find($study->getId());
        if (null !== $persistedStudy) {
            $persistedStudy->update($study);
            $study = $persistedStudy;
        }

        $this->entityManager->persist($study);
    }
}
