<?php

declare(strict_types=1);

namespace ITB\CmlifeClient\Tests\Storage;

use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Generator;
use ITB\CmlifeClient\Exception\StorageException;
use ITB\CmlifeClient\Model\Course;
use ITB\CmlifeClient\Model\Person;
use ITB\CmlifeClient\Model\Semester;
use ITB\CmlifeClient\Model\Study;
use ITB\CmlifeClient\Storage\DataStorage;
use PHPUnit\Framework\TestCase;

final class DataStorageTest extends TestCase
{
    /**
     * @return Generator
     * @throws StorageException
     */
    public function provideForTestPersistCourse(): Generator
    {
        include_once __DIR__ . '/../Fixtures/current_semester.php';
        $semester = Semester::create(getCurrentSemesterData());
        include_once __DIR__ . '/../Fixtures/course.php';
        $course = Course::create(getCourseData(), $semester);

        yield [DataStorage::createWithInMemorySqliteDatabase(), $course];
    }

    /**
     * @return Generator
     * @throws StorageException
     */
    public function provideForTestPersistCourseDouble(): Generator
    {
        include_once __DIR__ . '/../Fixtures/current_semester.php';
        $semester = Semester::create(getCurrentSemesterData());
        $semesterDouble = Semester::create(getCurrentSemesterData());
        include_once __DIR__ . '/../Fixtures/course.php';
        $course = Course::create(getCourseData(), $semester);
        $courseDouble = Course::create(getCourseData(), $semesterDouble);

        yield [DataStorage::createWithInMemorySqliteDatabase(), $course, $courseDouble];
    }

    /**
     * @return Generator
     * @throws StorageException
     */
    public function provideForTestPersistPersonDouble(): Generator
    {
        include_once __DIR__ . '/../Fixtures/me.php';
        $person = Person::create(getPersonMeData(), true);
        $personDouble = Person::create(getPersonMeData(), true);

        yield [DataStorage::createWithInMemorySqliteDatabase(), $person, $personDouble];
    }

    /**
     * @return Generator
     * @throws StorageException
     */
    public function provideForTestPersistSemesterDouble(): Generator
    {
        include_once __DIR__ . '/../Fixtures/me.php';
        $semester = Semester::create(getCurrentSemesterData());
        $semesterDouble = Semester::create(getCurrentSemesterData());

        yield [DataStorage::createWithInMemorySqliteDatabase(), $semester, $semesterDouble];
    }

    /**
     * @return Generator
     * @throws StorageException
     */
    public function provideForTestPersistStudyDouble(): Generator
    {
        include_once __DIR__ . '/../Fixtures/study.php';
        $study = Study::create(getStudyData());
        $studyDouble = Study::create(getStudyData());

        yield [DataStorage::createWithInMemorySqliteDatabase(), $study, $studyDouble];
    }

    /**
     * @dataProvider provideForTestPersistCourse
     *
     * @param DataStorage $dataStorage
     * @param Course $course
     * @return void
     * @throws ORMException
     */
    public function testPersistCourse(DataStorage $dataStorage, Course $course): void
    {
        $dataStorage->persistCourse($course);
        $this->assertEquals($course, $dataStorage->getCourseRepository()->find($course->getId()));
    }

    /**
     * @dataProvider provideForTestPersistCourseDouble
     *
     * @param DataStorage $dataStorage
     * @param Course $course
     * @param Course $courseDouble
     * @return void
     * @throws ORMException
     */
    public function testPersistCourseDouble(DataStorage $dataStorage, Course $course, Course $courseDouble): void
    {
        $dataStorage->persistSemester($course->getSemester());
        $dataStorage->persistCourse($course);
        $dataStorage->persistCourse($courseDouble);
        $dataStorage->flush();

        $this->assertCount(1, $dataStorage->getCourseRepository()->findAll());
    }

    /**
     * @dataProvider provideForTestPersistPersonDouble
     *
     * @param DataStorage $dataStorage
     * @param Person $person
     * @param Person $personDouble
     * @return void
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function testPersistPersonDouble(DataStorage $dataStorage, Person $person, Person $personDouble): void
    {
        $dataStorage->persistPerson($person);
        $dataStorage->persistPerson($personDouble);
        $dataStorage->flush();

        $this->assertCount(1, $dataStorage->getPersonRepository()->findAll());
    }

    /**
     * @dataProvider provideForTestPersistSemesterDouble
     *
     * @param DataStorage $dataStorage
     * @param Semester $semester
     * @param Semester $semesterDouble
     * @return void
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function testPersistSemesterDouble(DataStorage $dataStorage, Semester $semester, Semester $semesterDouble): void
    {
        $dataStorage->persistSemester($semester);
        $dataStorage->persistSemester($semesterDouble);
        $dataStorage->flush();

        $this->assertCount(1, $dataStorage->getSemesterRepository()->findAll());
    }

    /**
     * @dataProvider provideForTestPersistStudyDouble
     *
     * @param DataStorage $dataStorage
     * @param Study $study
     * @param Study $studyDouble
     * @return void
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function testPersistStudyDouble(DataStorage $dataStorage, Study $study, Study $studyDouble): void
    {
        $dataStorage->persistStudy($study);
        $dataStorage->persistStudy($studyDouble);
        $dataStorage->flush();

        $this->assertCount(1, $dataStorage->getStudyRepository()->findAll());
    }
}
