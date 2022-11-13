<?php

declare(strict_types=1);

namespace ITB\CmlifeClient\Storage;

use Doctrine\ORM\EntityManagerInterface;
use ITB\CmlifeClient\Model\Course;
use ITB\CmlifeClient\Model\Person;
use ITB\CmlifeClient\Model\Semester;
use ITB\CmlifeClient\Model\Study;
use ITB\CmlifeClient\Storage\Repository\CourseRepository;
use ITB\CmlifeClient\Storage\Repository\NodeRepository;
use ITB\CmlifeClient\Storage\Repository\PersonRepository;
use ITB\CmlifeClient\Storage\Repository\SemesterRepository;
use ITB\CmlifeClient\Storage\Repository\StudyRepository;

interface DataStorageInterface
{
    /**
     * @return void
     */
    public function flush(): void;

    /**
     * @return CourseRepository
     */
    public function getCourseRepository(): CourseRepository;

    /**
     * @return EntityManagerInterface
     */
    public function getEntityManager(): EntityManagerInterface;

    /**
     * @return NodeRepository
     */
    public function getNodeRepository(): NodeRepository;

    /**
     * @return PersonRepository
     */
    public function getPersonRepository(): PersonRepository;

    /**
     * @return SemesterRepository
     */
    public function getSemesterRepository(): SemesterRepository;

    /**
     * @return StudyRepository
     */
    public function getStudyRepository(): StudyRepository;

    /**
     * @param Course $course
     * @return void
     */
    public function persistCourse(Course $course): void;

    /**
     * @param Person $person
     * @return void
     */
    public function persistPerson(Person $person): void;

    /**
     * @param Semester $semester
     * @return void
     */
    public function persistSemester(Semester $semester): void;

    /**
     * @param Study $study
     * @return void
     */
    public function persistStudy(Study $study): void;
}
