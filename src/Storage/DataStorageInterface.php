<?php

declare(strict_types=1);

namespace ITB\CmlifeClient\Storage;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use ITB\CmlifeClient\Storage\Repository\CourseRepository;
use ITB\CmlifeClient\Storage\Repository\NodeRepository;
use ITB\CmlifeClient\Storage\Repository\PersonRepository;
use ITB\CmlifeClient\Storage\Repository\SemesterRepository;
use ITB\CmlifeClient\Storage\Repository\StudyRepository;

interface DataStorageInterface
{
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
}
