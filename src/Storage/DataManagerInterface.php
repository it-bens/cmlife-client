<?php

declare(strict_types=1);

namespace ITB\CmlifeClient\Storage;

use ITB\CmlifeClient\Model\Course;
use ITB\CmlifeClient\Model\Person;
use ITB\CmlifeClient\Model\Semester;
use ITB\CmlifeClient\Model\Study;

interface DataManagerInterface
{
    /**
     * @return void
     */
    public function flush(): void;

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
