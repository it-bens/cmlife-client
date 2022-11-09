<?php

declare(strict_types=1);

namespace ITB\CmlifeClient\Storage\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Exception;
use ITB\CmlifeClient\Model\Course;
use ITB\CmlifeClient\Model\Curriculum\Node\LinkNode;
use ITB\CmlifeClient\Model\Semester;
use ITB\CmlifeClient\Storage\Exception\CourseSearchWithLinkNodesAndSemesterFailedException;

final class CourseRepository
{
    /** @var EntityRepository<Course> */
    private readonly EntityRepository $entityRepository;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
        $this->entityRepository = $entityManager->getRepository(Course::class);
    }

    /**
     * @param int $courseId
     * @return Course|null
     */
    public function find(int $courseId): ?Course
    {
        return $this->entityRepository->find($courseId);
    }

    /**
     * @param LinkNode[] $linkNodes
     * @return Course[]
     * @throws CourseSearchWithLinkNodesAndSemesterFailedException
     */
    public function findByLinkNodesAndSemester(array $linkNodes, Semester $semester): array
    {
        $courseEquivalenceUris = array_map(static function (LinkNode $node): ?string {
            return $node->getCourseEquivalenceUri();
        }, $linkNodes);
        // Some Link nodes contain no equivalence URI. These null-URIs must be removed.
        $courseEquivalenceUris = array_filter($courseEquivalenceUris);

        $queryBuilder = $this->entityRepository->createQueryBuilder('course')
            ->andWhere($this->entityManager->createQueryBuilder()->expr()->in('course.equivalenceUri', ':equivalenceUris'))
            ->setParameter('equivalenceUris', $courseEquivalenceUris)
            ->andWhere($this->entityManager->createQueryBuilder()->expr()->eq('course.semester', ':semester'))
            ->setParameter('semester', $semester);
        $query = $queryBuilder->getQuery();

        try {
            return $query->getResult();
        } catch (Exception $exception) {
            throw CourseSearchWithLinkNodesAndSemesterFailedException::create($exception);
        }
    }

    /**
     * @param Semester $semester
     * @return Course[]
     */
    public function findBySemester(Semester $semester): array
    {
        return $this->entityRepository->findBy(['semester' => $semester]);
    }
}
