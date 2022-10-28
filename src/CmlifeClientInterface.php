<?php

declare(strict_types=1);

namespace ITB\CmlifeClient;

use ITB\CmlifeClient\Model\Course;
use ITB\CmlifeClient\Model\Person;
use ITB\CmlifeClient\Model\Semester;
use ITB\CmlifeClient\Model\Study;

interface CmlifeClientInterface
{
    /**
     * @return int
     */
    public static function getCurrentSemesterId(): int;

    /**
     * @return void
     */
    public function fetchDataFromCmlife(): void;

    /**
     * @param Semester $semester
     * @return Course[]
     */
    public function getCourses(Semester $semester): array;

    /**
     * @param Study $study
     * @param Semester $semester
     * @return Course[]
     */
    public function getCoursesForStudyAndSemester(Study $study, Semester $semester): array;

    /**
     * @return Person
     */
    public function getCurrentPerson(): Person;

    /**
     * @return Semester
     */
    public function getCurrentSemester(): Semester;

    /**
     * @return Study[]
     */
    public function getMyStudies(): array;
}
