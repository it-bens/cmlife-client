<?php

declare(strict_types=1);

namespace ITB\CmlifeClient\Storage;

use ITB\CmlifeClient\Exception\StorageException;
use ITB\CmlifeClient\Model\Course;
use ITB\CmlifeClient\Model\Curriculum\Node\LinkNode;
use ITB\CmlifeClient\Model\Curriculum\NodeInterface;
use ITB\CmlifeClient\Model\Person;
use ITB\CmlifeClient\Model\Semester;
use ITB\CmlifeClient\Model\Study;

interface DataRepositoryInterface
{
    /**
     * @return Course[]
     */
    public function findAllCourses(): array;

    /**
     * @return Person[]
     */
    public function findAllPersons(): array;

    /**
     * @return Semester[]
     */
    public function findAllSemesters(): array;

    /**
     * @return Study[]
     */
    public function findAllStudies(): array;

    /**
     * @param int $courseId
     * @return Course|null
     */
    public function findCourse(int $courseId): ?Course;

    /**
     * @param LinkNode[] $linkNodes
     * @return Course[]
     */
    public function findCoursesByLinkNodesAndSemester(array $linkNodes, Semester $semester): array;

    /**
     * @param Semester $semester
     * @return Course[]
     */
    public function findCoursesBySemester(Semester $semester): array;

    /**
     * @return Semester
     * @throws StorageException
     */
    public function findCurrentSemester(): Semester;

    /**
     * @return Person
     * @throws StorageException
     */
    public function findMe(): Person;

    /**
     * @param string $nodeType
     * @param Study $study
     * @return NodeInterface[]
     */
    public function findNodesByTypeAndStudy(string $nodeType, Study $study): array;

    /**
     * @param string $personUri
     * @return Person|null
     */
    public function findPerson(string $personUri): ?Person;

    /**
     * @param int $semesterId
     * @return Semester|null
     */
    public function findSemester(int $semesterId): ?Semester;

    /**
     * @param int $studyId
     * @return Study|null
     */
    public function findStudy(int $studyId): ?Study;
}
