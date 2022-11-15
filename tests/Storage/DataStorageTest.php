<?php

declare(strict_types=1);

namespace ITB\CmlifeClient\Tests\Storage;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\ORMSetup;
use Generator;
use ITB\CmlifeClient\Exception\StorageException;
use ITB\CmlifeClient\Model\Course;
use ITB\CmlifeClient\Model\Curriculum\Node\LinkNode;
use ITB\CmlifeClient\Model\Curriculum\NodeInterface;
use ITB\CmlifeClient\Model\Person;
use ITB\CmlifeClient\Model\Semester;
use ITB\CmlifeClient\Model\Study;
use ITB\CmlifeClient\Storage\DataManagerInterface;
use ITB\CmlifeClient\Storage\DataRepositoryInterface;
use ITB\CmlifeClient\Storage\DoctrineDataStorage;
use ITB\CmlifeClient\Storage\Exception\MoreThanOnePersonIsMeException;
use ITB\CmlifeClient\Storage\Exception\MoreThanOneSemesterIsCurrentException;
use ITB\CmlifeClient\Storage\Exception\NoPersonIsMeException;
use ITB\CmlifeClient\Storage\Exception\NoSemesterIsCurrentException;
use ITB\CmlifeClient\Tests\CreateDoctrineDataStorageTrait;
use PHPUnit\Framework\TestCase;

final class DataStorageTest extends TestCase
{
    use CreateDoctrineDataStorageTrait;

    /**
     * @return Generator
     * @throws ORMException
     */
    public function provideForTestConstruct(): Generator
    {
        $config = ORMSetup::createAttributeMetadataConfiguration(paths: [__DIR__ . '/src/Model'], isDevMode: false);
        $entityManager = EntityManager::create(['driver' => 'pdo_sqlite', 'memory' => true], $config);

        yield [$entityManager];
    }

    /**
     * @return Generator
     * @throws ORMException
     */
    public function provideForTestFindAllCourses(): Generator
    {
        $storage = self::createDoctrineDataStorage();

        include_once __DIR__ . '/../Fixtures/current_semester.php';
        $currentSemester = Semester::create(getCurrentSemesterData());
        $storage->persistSemester($currentSemester);

        include_once __DIR__ . '/../Fixtures/course.php';
        $course1 = Course::create(getCourseData(), $currentSemester);
        $storage->persistCourse($course1);

        $course2Data = getCourseData();
        $course2Data['id'] = 308610;
        $course2Data['uri'] = '//ubt@cmco/api/courses/308610';
        $course2 = Course::create($course2Data, $currentSemester);
        $storage->persistCourse($course2);

        $storage->flush();

        yield [$storage, 2];
    }

    /**
     * @return Generator
     * @throws ORMException
     */
    public function provideForTestFindAllPersons(): Generator
    {
        $storage = self::createDoctrineDataStorage();

        include_once __DIR__ . '/../Fixtures/me.php';
        $me = Person::create(getPersonMeData(), true);
        $storage->persistPerson($me);

        include_once __DIR__ . '/../Fixtures/not_me.php';
        $notMe = Person::create(getPersonNotMeData(), false);
        $storage->persistPerson($notMe);

        $storage->flush();

        yield [$storage, 2];
    }

    /**
     * @return Generator
     * @throws ORMException
     */
    public function provideForTestFindAllSemesters(): Generator
    {
        $storage = self::createDoctrineDataStorage();

        include_once __DIR__ . '/../Fixtures/current_semester.php';
        $currentSemester = Semester::create(getCurrentSemesterData());
        $storage->persistSemester($currentSemester);

        include_once __DIR__ . '/../Fixtures/previous_semester.php';
        $previousSemester = Semester::create(getPreviousSemesterData());
        $storage->persistSemester($previousSemester);

        $storage->flush();

        yield [$storage, 2];
    }

    /**
     * @return Generator
     * @throws ORMException
     */
    public function provideForTestFindAllStudies(): Generator
    {
        $storage = self::createDoctrineDataStorage();

        include_once __DIR__ . '/../Fixtures/study.php';
        $study = Study::create(getStudyData());
        $storage->persistStudy($study);

        $storage->flush();

        yield [$storage, 1];
    }

    /**
     * @return Generator
     * @throws ORMException
     */
    public function provideForTestFindCourse(): Generator
    {
        $storage = self::createDoctrineDataStorage();

        include_once __DIR__ . '/../Fixtures/current_semester.php';
        $currentSemester = Semester::create(getCurrentSemesterData());
        $storage->persistSemester($currentSemester);

        include_once __DIR__ . '/../Fixtures/course.php';
        $course = Course::create(getCourseData(), $currentSemester);
        $storage->persistCourse($course);

        $storage->flush();

        yield [$storage, $course->getId()];
    }

    /**
     * @return Generator
     * @throws ORMException
     */
    public function provideForTestFindCoursesByLinkNodesAndSemester(): Generator
    {
        $storage = self::createDoctrineDataStorage();

        include_once __DIR__ . '/../Fixtures/current_semester.php';
        $currentSemester = Semester::create(getCurrentSemesterData());
        $storage->persistSemester($currentSemester);

        include_once __DIR__ . '/../Fixtures/course.php';
        $course = Course::create(getCourseData(), $currentSemester);
        $storage->persistCourse($course);

        include_once __DIR__ . '/../Fixtures/node/link_node.php';
        $linkNodeData = getLinkNodeData();
        $linkNodeData['courseEquivalenceUri'] = '//ubt@cmco/api/courseEquivalences/85516';
        $linkNode = LinkNode::create($linkNodeData);

        include_once __DIR__ . '/../Fixtures/study.php';
        $studyData = getStudyData();
        $studyData['curriculum']['root']['children'] = [$linkNodeData];
        $study = Study::create($studyData);
        $storage->persistStudy($study);

        $storage->flush();

        yield [$storage, [$linkNode], $currentSemester, [$course]];
    }

    /**
     * @return Generator
     * @throws ORMException
     */
    public function provideForTestFindCoursesBySemester(): Generator
    {
        $storage = self::createDoctrineDataStorage();

        include_once __DIR__ . '/../Fixtures/current_semester.php';
        $currentSemester = Semester::create(getCurrentSemesterData());
        $storage->persistSemester($currentSemester);

        include_once __DIR__ . '/../Fixtures/course.php';
        $course1 = Course::create(getCourseData(), $currentSemester);
        $storage->persistCourse($course1);

        $course2Data = getCourseData();
        $course2Data['id'] = 308610;
        $course2Data['uri'] = '//ubt@cmco/api/courses/308610';
        $course2 = Course::create($course2Data, $currentSemester);
        $storage->persistCourse($course2);

        include_once __DIR__ . '/../Fixtures/previous_semester.php';
        $previousSemester = Semester::create(getPreviousSemesterData());
        $storage->persistSemester($previousSemester);

        $course3Data = getCourseData();
        $course3Data['id'] = 308611;
        $course3Data['uri'] = '//ubt@cmco/api/courses/308611';
        $course3 = Course::create($course3Data, $previousSemester);
        $storage->persistCourse($course3);

        $storage->flush();

        yield [$storage, $currentSemester, [$course1, $course2]];
    }

    /**
     * @return Generator
     * @throws ORMException
     */
    public function provideForTestFindCurrentSemester(): Generator
    {
        include_once __DIR__ . '/../Fixtures/current_semester.php';
        $semester = Semester::create(getCurrentSemesterData());

        $storage = self::createDoctrineDataStorage();
        $storage->persistSemester($semester);

        $storage->flush();

        yield [$storage, $semester->getId()];
    }

    /**
     * @return Generator
     * @throws ORMException
     */
    public function provideForTestFindCurrentSemesterWithNoCurrentSemester(): Generator
    {
        yield [self::createDoctrineDataStorage()];
    }

    /**
     * @return Generator
     * @throws ORMException
     */
    public function provideForTestFindMe(): Generator
    {
        include_once __DIR__ . '/../Fixtures/me.php';
        $person = Person::create(getPersonMeData(), true);

        $storage = self::createDoctrineDataStorage();
        $storage->persistPerson($person);

        $storage->flush();

        yield [$storage, $person->getUri()];
    }

    /**
     * @return Generator
     * @throws ORMException
     */
    public function provideForTestFindMeWithNoMe(): Generator
    {
        include_once __DIR__ . '/../Fixtures/me.php';
        $person = Person::create(getPersonMeData(), true);

        yield [self::createDoctrineDataStorage(), $person->getUri()];
    }

    /**
     * @return Generator
     * @throws ORMException
     */
    public function provideForTestFindNodesByTypeAndStudy(): Generator
    {
        $storage = self::createDoctrineDataStorage();

        include_once __DIR__ . '/../Fixtures/node/link_node.php';
        $linkNodeData = getLinkNodeData();
        $linkNodeData['courseEquivalenceUri'] = '//ubt@cmco/api/courseEquivalences/85516';
        $linkNode = LinkNode::create($linkNodeData);

        include_once __DIR__ . '/../Fixtures/study.php';
        $studyData = getStudyData();
        $studyData['curriculum']['root']['children'] = [$linkNodeData];
        $study = Study::create($studyData);
        $storage->persistStudy($study);

        $storage->flush();

        yield [$storage, LinkNode::TYPE, $study, [$linkNode]];
    }

    /**
     * @return Generator
     * @throws ORMException
     */
    public function provideForTestFindPerson(): Generator
    {
        include_once __DIR__ . '/../Fixtures/me.php';
        $person = Person::create(getPersonMeData(), true);

        $storage = self::createDoctrineDataStorage();
        $storage->persistPerson($person);

        $storage->flush();

        yield [$storage, $person->getUri()];
    }

    /**
     * @return Generator
     * @throws ORMException
     */
    public function provideForTestFindPersonNot(): Generator
    {
        include_once __DIR__ . '/../Fixtures/me.php';
        $person = Person::create(getPersonMeData(), true);

        yield [self::createDoctrineDataStorage(), $person->getUri()];
    }

    /**
     * @return Generator
     * @throws ORMException
     */
    public function provideForTestFindSemester(): Generator
    {
        include_once __DIR__ . '/../Fixtures/current_semester.php';
        $semester = Semester::create(getCurrentSemesterData());

        $storage = self::createDoctrineDataStorage();
        $storage->persistSemester($semester);

        $storage->flush();

        yield [$storage, $semester->getId()];
    }

    /**
     * @return Generator
     * @throws ORMException
     */
    public function provideForTestFindSemesterNot(): Generator
    {
        include_once __DIR__ . '/../Fixtures/current_semester.php';
        $semester = Semester::create(getCurrentSemesterData());

        yield [self::createDoctrineDataStorage(), $semester->getId()];
    }

    /**
     * @return Generator
     * @throws ORMException
     */
    public function provideForTestFindStudy(): Generator
    {
        include_once __DIR__ . '/../Fixtures/study.php';
        $study = Study::create(getStudyData());

        $storage = self::createDoctrineDataStorage();
        $storage->persistStudy($study);

        $storage->flush();

        yield [$storage, $study->getId()];
    }

    /**
     * @return Generator
     * @throws ORMException
     */
    public function provideForTestFindStudyNot(): Generator
    {
        include_once __DIR__ . '/../Fixtures/study.php';
        $study = Study::create(getStudyData());

        yield [self::createDoctrineDataStorage(), $study->getId()];
    }

    /**
     * @return Generator
     * @throws ORMException
     * @throws StorageException
     */
    public function provideForTestPersistCourse(): Generator
    {
        include_once __DIR__ . '/../Fixtures/current_semester.php';
        $semester = Semester::create(getCurrentSemesterData());
        include_once __DIR__ . '/../Fixtures/course.php';
        $course = Course::create(getCourseData(), $semester);

        yield [self::createDoctrineDataStorage(), $course];
    }

    /**
     * @return Generator
     * @throws ORMException
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

        yield [self::createDoctrineDataStorage(), $course, $courseDouble];
    }

    /**
     * @return Generator
     * @throws ORMException
     * @throws StorageException
     */
    public function provideForTestPersistPersonDouble(): Generator
    {
        include_once __DIR__ . '/../Fixtures/me.php';
        $person = Person::create(getPersonMeData(), true);
        $personDouble = Person::create(getPersonMeData(), true);

        yield [self::createDoctrineDataStorage(), $person, $personDouble];
    }

    /**
     * @return Generator
     * @throws ORMException
     */
    public function provideForTestPersistPersonWithMeAlreadyExisting(): Generator
    {
        $storage = self::createDoctrineDataStorage();

        include_once __DIR__ . '/../Fixtures/me.php';
        $me = Person::create(getPersonMeData(), true);
        $storage->persistPerson($me);

        include_once __DIR__ . '/../Fixtures/not_me.php';
        $notMe = Person::create(getPersonNotMeData(), true);

        $storage->flush();

        yield [$storage, $notMe];
    }

    /**
     * @return Generator
     * @throws ORMException
     * @throws StorageException
     */
    public function provideForTestPersistSemesterDouble(): Generator
    {
        include_once __DIR__ . '/../Fixtures/current_semester.php';
        $semester = Semester::create(getCurrentSemesterData());
        $semesterDouble = Semester::create(getCurrentSemesterData());

        yield [self::createDoctrineDataStorage(), $semester, $semesterDouble];
    }

    /**
     * @return Generator
     * @throws ORMException
     */
    public function provideForTestPersistSemesterWithCurrentSemesterAlreadyExisting(): Generator
    {
        $storage = self::createDoctrineDataStorage();

        include_once __DIR__ . '/../Fixtures/current_semester.php';
        $currentSemester = Semester::create(getCurrentSemesterData());
        $storage->persistSemester($currentSemester);

        include_once __DIR__ . '/../Fixtures/previous_semester.php';
        $previousSemesterData = getPreviousSemesterData();
        $previousSemesterData['current'] = true;
        $previousSemester = Semester::create($previousSemesterData);

        $storage->flush();

        yield [$storage, $previousSemester];
    }

    /**
     * @return Generator
     * @throws ORMException
     * @throws StorageException
     */
    public function provideForTestPersistStudyDouble(): Generator
    {
        include_once __DIR__ . '/../Fixtures/study.php';
        $study = Study::create(getStudyData());
        $studyDouble = Study::create(getStudyData());

        yield [self::createDoctrineDataStorage(), $study, $studyDouble];
    }

    /**
     * @dataProvider provideForTestConstruct
     *
     * @param EntityManagerInterface $entityManager
     * @return void
     */
    public function testConstruct(EntityManagerInterface $entityManager): void
    {
        $storage = new DoctrineDataStorage($entityManager);
        $this->assertInstanceOf(DataManagerInterface::class, $storage);
        $this->assertInstanceOf(DataRepositoryInterface::class, $storage);
    }

    /**
     * @dataProvider provideForTestFindAllCourses
     *
     * @param DoctrineDataStorage $dataStorage
     * @param int $expectedCourseCount
     * @return void
     */
    public function testFindAllCourses(DoctrineDataStorage $dataStorage, int $expectedCourseCount): void
    {
        $this->assertCount($expectedCourseCount, $dataStorage->findAllCourses());
    }

    /**
     * @dataProvider provideForTestFindAllPersons
     *
     * @param DoctrineDataStorage $dataStorage
     * @param int $expectedPersonCount
     * @return void
     */
    public function testFindAllPersons(DoctrineDataStorage $dataStorage, int $expectedPersonCount): void
    {
        $this->assertCount($expectedPersonCount, $dataStorage->findAllPersons());
    }

    /**
     * @dataProvider provideForTestFindAllSemesters
     *
     * @param DoctrineDataStorage $dataStorage
     * @param int $expectedSemesterCount
     * @return void
     */
    public function testFindAllSemesters(DoctrineDataStorage $dataStorage, int $expectedSemesterCount): void
    {
        $this->assertCount($expectedSemesterCount, $dataStorage->findAllSemesters());
    }

    /**
     * @dataProvider provideForTestFindAllStudies
     *
     * @param DoctrineDataStorage $dataStorage
     * @param int $expectedSemesterCount
     * @return void
     */
    public function testFindAllStudies(DoctrineDataStorage $dataStorage, int $expectedSemesterCount): void
    {
        $this->assertCount($expectedSemesterCount, $dataStorage->findAllStudies());
    }

    /**
     * @dataProvider provideForTestFindCourse
     *
     * @param DoctrineDataStorage $dataStorage
     * @param int $courseId
     * @return void
     */
    public function testFindCourse(DoctrineDataStorage $dataStorage, int $courseId): void
    {
        $result = $dataStorage->findCourse($courseId);
        $this->assertInstanceOf(Course::class, $result);
        $this->assertEquals($courseId, $result->getId());
    }

    /**
     * @dataProvider provideForTestFindCoursesByLinkNodesAndSemester
     *
     * @param DoctrineDataStorage $dataStorage
     * @param LinkNode[] $linkNodes
     * @param Semester $semester
     * @param Course[] $expectedCourses
     * @return void
     */
    public function testFindCoursesByLinkNodesAndSemester(
        DoctrineDataStorage $dataStorage,
        array $linkNodes,
        Semester $semester,
        array $expectedCourses
    ): void {
        $results = $dataStorage->findCoursesByLinkNodesAndSemester($linkNodes, $semester);
        $this->assertCount(count($expectedCourses), $results);
        foreach ($results as $result) {
            $matchingCourseInExpectedCourses = array_filter($expectedCourses, static function (Course $course) use ($result): bool {
                return $result->getId() === $course->getId();
            });
            $this->assertCount(1, $matchingCourseInExpectedCourses);
        }
    }

    /**
     * @dataProvider provideForTestFindCoursesBySemester
     *
     * @param DoctrineDataStorage $dataStorage
     * @param Semester $semester
     * @param Course[] $expectedCourses
     * @return void
     */
    public function testFindCoursesBySemester(DoctrineDataStorage $dataStorage, Semester $semester, array $expectedCourses): void
    {
        $results = $dataStorage->findCoursesBySemester($semester);
        $this->assertCount(count($expectedCourses), $results);
        foreach ($results as $result) {
            $matchingCourseInExpectedCourses = array_filter($expectedCourses, static function (Course $course) use ($result): bool {
                return $result->getId() === $course->getId();
            });
            $this->assertCount(1, $matchingCourseInExpectedCourses);
        }
    }

    /**
     * @dataProvider provideForTestFindCurrentSemester
     *
     * @param DoctrineDataStorage $dataStorage
     * @param int $expectedSemesterId
     * @return void
     * @throws StorageException
     */
    public function testFindCurrentSemester(DoctrineDataStorage $dataStorage, int $expectedSemesterId): void
    {
        $result = $dataStorage->findCurrentSemester();
        $this->assertInstanceOf(Semester::class, $result);
        $this->assertEquals($expectedSemesterId, $result->getId());
    }

    /**
     * @dataProvider provideForTestFindCurrentSemesterWithNoCurrentSemester
     *
     * @param DoctrineDataStorage $dataStorage
     * @return void
     * @throws StorageException
     */
    public function testFindCurrentSemesterWithNoCurrentSemester(DoctrineDataStorage $dataStorage): void
    {
        $this->expectException(NoSemesterIsCurrentException::class);
        $dataStorage->findCurrentSemester();
    }

    /**
     * @dataProvider provideForTestFindMe
     *
     * @param DoctrineDataStorage $dataStorage
     * @return void
     * @throws StorageException
     */
    public function testFindMe(DoctrineDataStorage $dataStorage): void
    {
        $result = $dataStorage->findMe();
        $this->assertInstanceOf(Person::class, $result);
        $this->assertTrue($result->isMe());
    }

    /**
     * @dataProvider provideForTestFindMeWithNoMe
     *
     * @param DoctrineDataStorage $dataStorage
     * @return void
     * @throws StorageException
     */
    public function testFindMeWithNoMe(DoctrineDataStorage $dataStorage): void
    {
        $this->expectException(NoPersonIsMeException::class);
        $dataStorage->findMe();
    }

    /**
     * @dataProvider provideForTestFindNodesByTypeAndStudy
     *
     * @param DoctrineDataStorage $dataStorage
     * @param string $nodeType
     * @param Study $study
     * @param NodeInterface[] $expectedNodes
     * @return void
     */
    public function testFindNodesByTypeAndStudy(DoctrineDataStorage $dataStorage, string $nodeType, Study $study, array $expectedNodes): void
    {
        $results = $dataStorage->findNodesByTypeAndStudy($nodeType, $study);
        $this->assertCount(count($expectedNodes), $results);
        foreach ($results as $result) {
            $matchingCourseInExpectedNodes = array_filter($expectedNodes, static function (NodeInterface $node) use ($result): bool {
                return $result->getId() === $node->getId();
            });
            $this->assertCount(1, $matchingCourseInExpectedNodes);
        }
    }

    /**
     * @dataProvider provideForTestFindPerson
     *
     * @param DoctrineDataStorage $dataStorage
     * @param string $personUri
     * @return void
     */
    public function testFindPerson(DoctrineDataStorage $dataStorage, string $personUri): void
    {
        $result = $dataStorage->findPerson($personUri);
        $this->assertInstanceOf(Person::class, $result);
        $this->assertEquals($personUri, $result->getUri());
    }

    /**
     * @dataProvider provideForTestFindPersonNot
     *
     * @param DoctrineDataStorage $dataStorage
     * @param string $personUri
     * @return void
     */
    public function testFindPersonNot(DoctrineDataStorage $dataStorage, string $personUri): void
    {
        $this->assertNull($dataStorage->findPerson($personUri));
    }

    /**
     * @dataProvider provideForTestFindSemester
     *
     * @param DoctrineDataStorage $dataStorage
     * @param int $semesterId
     * @return void
     */
    public function testFindSemester(DoctrineDataStorage $dataStorage, int $semesterId): void
    {
        $result = $dataStorage->findSemester($semesterId);
        $this->assertInstanceOf(Semester::class, $result);
        $this->assertEquals($semesterId, $result->getId());
    }

    /**
     * @dataProvider provideForTestFindSemesterNot
     *
     * @param DoctrineDataStorage $dataStorage
     * @param int $semesterId
     * @return void
     */
    public function testFindSemesterNot(DoctrineDataStorage $dataStorage, int $semesterId): void
    {
        $this->assertNull($dataStorage->findSemester($semesterId));
    }

    /**
     * @dataProvider provideForTestFindStudy
     *
     * @param DoctrineDataStorage $dataStorage
     * @param int $studyId
     * @return void
     */
    public function testFindStudy(DoctrineDataStorage $dataStorage, int $studyId): void
    {
        $result = $dataStorage->findStudy($studyId);
        $this->assertInstanceOf(Study::class, $result);
        $this->assertEquals($studyId, $result->getId());
    }

    /**
     * @dataProvider provideForTestFindStudyNot
     *
     * @param DoctrineDataStorage $dataStorage
     * @param int $studyId
     * @return void
     */
    public function testFindStudyNot(DoctrineDataStorage $dataStorage, int $studyId): void
    {
        $this->assertNull($dataStorage->findStudy($studyId));
    }

    /**
     * @dataProvider provideForTestPersistCourse
     *
     * @param DoctrineDataStorage $dataStorage
     * @param Course $course
     * @return void
     */
    public function testPersistCourse(DoctrineDataStorage $dataStorage, Course $course): void
    {
        $dataStorage->persistCourse($course);
        $this->assertEquals($course, $dataStorage->findCourse($course->getId()));
    }

    /**
     * @dataProvider provideForTestPersistCourseDouble
     *
     * @param DoctrineDataStorage $dataStorage
     * @param Course $course
     * @param Course $courseDouble
     * @return void
     */
    public function testPersistCourseDouble(DoctrineDataStorage $dataStorage, Course $course, Course $courseDouble): void
    {
        $dataStorage->persistSemester($course->getSemester());
        $dataStorage->persistCourse($course);
        $dataStorage->persistCourse($courseDouble);
        $dataStorage->flush();

        $this->assertCount(1, $dataStorage->findAllCourses());
    }

    /**
     * @dataProvider provideForTestPersistPersonDouble
     *
     * @param DoctrineDataStorage $dataStorage
     * @param Person $person
     * @param Person $personDouble
     * @return void
     */
    public function testPersistPersonDouble(DoctrineDataStorage $dataStorage, Person $person, Person $personDouble): void
    {
        $dataStorage->persistPerson($person);
        $dataStorage->persistPerson($personDouble);
        $dataStorage->flush();

        $this->assertCount(1, $dataStorage->findAllPersons());
    }

    /**
     * @dataProvider provideForTestPersistPersonWithMeAlreadyExisting
     *
     * @param DoctrineDataStorage $dataStorage
     * @param Person $person
     * @return void
     */
    public function testPersistPersonWithMeAlreadyExisting(DoctrineDataStorage $dataStorage, Person $person): void
    {
        $this->expectException(MoreThanOnePersonIsMeException::class);
        $dataStorage->persistPerson($person);
    }

    /**
     * @dataProvider provideForTestPersistSemesterDouble
     *
     * @param DoctrineDataStorage $dataStorage
     * @param Semester $semester
     * @param Semester $semesterDouble
     * @return void
     */
    public function testPersistSemesterDouble(DoctrineDataStorage $dataStorage, Semester $semester, Semester $semesterDouble): void
    {
        $dataStorage->persistSemester($semester);
        $dataStorage->persistSemester($semesterDouble);
        $dataStorage->flush();

        $this->assertCount(1, $dataStorage->findAllSemesters());
    }

    /**
     * @dataProvider provideForTestPersistSemesterWithCurrentSemesterAlreadyExisting
     *
     * @param DoctrineDataStorage $dataStorage
     * @param Semester $semester
     * @return void
     */
    public function testPersistSemesterWithCurrentSemesterAlreadyExisting(DoctrineDataStorage $dataStorage, Semester $semester): void
    {
        $this->expectException(MoreThanOneSemesterIsCurrentException::class);
        $dataStorage->persistSemester($semester);
    }

    /**
     * @dataProvider provideForTestPersistStudyDouble
     *
     * @param DoctrineDataStorage $dataStorage
     * @param Study $study
     * @param Study $studyDouble
     * @return void
     */
    public function testPersistStudyDouble(DoctrineDataStorage $dataStorage, Study $study, Study $studyDouble): void
    {
        $dataStorage->persistStudy($study);
        $dataStorage->persistStudy($studyDouble);
        $dataStorage->flush();

        $this->assertCount(1, $dataStorage->findAllStudies());
    }
}
