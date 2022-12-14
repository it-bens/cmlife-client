<?php

declare(strict_types=1);

namespace ITB\CmlifeClient;

use DateTimeImmutable;
use GuzzleHttp\Promise\Utils;
use Http\Promise\Promise;
use ITB\CmlifeClient\Connection\DataClientInterface;
use ITB\CmlifeClient\Connection\RequestData;
use ITB\CmlifeClient\Exception\AuthenticationException;
use ITB\CmlifeClient\Exception\CmlifeDataNotFetchedException;
use ITB\CmlifeClient\Exception\CourseRetrievalFailedException;
use ITB\CmlifeClient\Exception\MyStudiesRetrievalFailedException;
use ITB\CmlifeClient\Exception\PersonRetrievalFailedException;
use ITB\CmlifeClient\Exception\SemesterRetrievalFailedException;
use ITB\CmlifeClient\Exception\StorageException;
use ITB\CmlifeClient\Exception\StudyRetrievalFailedException;
use ITB\CmlifeClient\Model\Course;
use ITB\CmlifeClient\Model\Curriculum\Node;
use ITB\CmlifeClient\Model\Person;
use ITB\CmlifeClient\Model\Semester;
use ITB\CmlifeClient\Model\Study;
use ITB\CmlifeClient\Storage\DataManagerInterface;
use ITB\CmlifeClient\Storage\DataRepositoryInterface;
use JsonException;

final class CmlifeClient implements CmlifeClientInterface
{
    private const CURRENT_PERSON_ENDPOINT_URI = '/v3/cmco/api/user';

    private const SEMESTER_ENDPOINT_URI = '/v3/cmco/api/semesters/';

    private const COURSE_ENDPOINT_URI = '/v3/cmco/api/courses/pages/searches';
    private const COURSE_ENDPOINT_LIMIT = 500;

    private const MY_STUDIES_ENDPOINT_URI = '/v3/cmco/api/studies/searches';

    private const STUDY_ENDPOINT_URI = '/v3/cmco/api/studies/';

    /**
     * Indicates if data were already fetched from cmlife.
     * Trying to access data without fetching them first will lead to an exception.
     *
     * @var bool
     */
    private bool $dataFetched = false;

    /**
     * @param DataClientInterface $dataClient
     * @param DataManagerInterface $dataManager
     * @param DataRepositoryInterface $dataRepository
     */
    public function __construct(
        private readonly DataClientInterface $dataClient,
        private readonly DataManagerInterface $dataManager,
        private readonly DataRepositoryInterface $dataRepository
    ) {
    }

    /**
     * The calculation is reversed engineered with educated guesses.
     * I have know idea if this is correct, but it works for now.
     *
     * @return int
     */
    public static function getCurrentSemesterId(): int
    {
        $firstSemesterBegin = new DateTimeImmutable('01.10.1901');
        $intervalSinceFirstSemesterBegin = (new DateTimeImmutable())->diff($firstSemesterBegin);
        $months = (int)$intervalSinceFirstSemesterBegin->format('%y') * 12 + (int)$intervalSinceFirstSemesterBegin->format('%m');
        $semester = (int)floor($months / 6.0);

        return $semester + 1;
    }

    /**
     * @return void
     * @throws AuthenticationException
     * @throws StorageException
     */
    public function fetchDataFromCmlife(): void
    {
        $currentSemesterId = $this->getCurrentSemesterId();
        $currentSemesterPromise = $this->fetchSemester($currentSemesterId);
        $previousSemesterPromise = $this->fetchSemester($currentSemesterId - 1);
        $nextSemesterPromise = $this->fetchSemester($currentSemesterId + 1);

        $currentPersonPromise = $this->fetchCurrentPerson();

        $currentSemesterPromise->wait(false);
        $previousSemesterPromise->wait(false);
        $nextSemesterPromise->wait(false);
        $currentPersonPromise->wait(false);
        $this->dataManager->flush();

        $coursePromises = [];
        foreach ($this->dataRepository->findAllSemesters() as $semester) {
            $coursePromises = array_merge($coursePromises, $this->fetchCourses($semester));
        }
        Utils::all($coursePromises)->wait();
        $this->dataManager->flush();

        $me = $this->dataRepository->findMe();
        $studiesPromises = $this->fetchMyStudies($me);
        Utils::all($studiesPromises)->wait();
        $this->dataManager->flush();

        $this->dataFetched = true;
    }

    /**
     * @return Course[]
     */
    public function getCourses(Semester $semester): array
    {
        if (false === $this->dataFetched) {
            throw CmlifeDataNotFetchedException::create();
        }

        return $this->dataRepository->findCoursesBySemester($semester);
    }

    /**
     * @param Study $study
     * @param Semester $semester
     * @return Course[]
     */
    public function getCoursesForStudyAndSemester(Study $study, Semester $semester): array
    {
        if (false === $this->dataFetched) {
            throw CmlifeDataNotFetchedException::create();
        }

        /** @var Node\LinkNode[] $nodes */
        $nodes = $this->dataRepository->findNodesByTypeAndStudy(Node\LinkNode::TYPE, $study);

        return $this->dataRepository->findCoursesByLinkNodesAndSemester($nodes, $semester);
    }

    /**
     * @return Person
     * @throws StorageException
     */
    public function getCurrentPerson(): Person
    {
        if (false === $this->dataFetched) {
            throw CmlifeDataNotFetchedException::create();
        }

        return $this->dataRepository->findMe();
    }

    /**
     * @return Semester
     * @throws StorageException
     */
    public function getCurrentSemester(): Semester
    {
        if (false === $this->dataFetched) {
            throw CmlifeDataNotFetchedException::create();
        }

        return $this->dataRepository->findCurrentSemester();
    }

    /**
     * @return Study[]
     */
    public function getMyStudies(): array
    {
        if (false === $this->dataFetched) {
            throw CmlifeDataNotFetchedException::create();
        }

        return $this->dataRepository->findAllStudies();
    }

    /**
     * @param Semester $semester
     * @return Promise[]
     * @throws AuthenticationException
     * @throws JsonException
     */
    private function fetchCourses(Semester $semester): array
    {
        $requestData = RequestData::create('POST', self::COURSE_ENDPOINT_URI, ['size' => 0], ['semesterUri' => $semester->getUri()]);
        $totalCoursesContent = $this->dataClient->fetchDataSync($requestData);
        try {
            $totalCourses = json_decode($totalCoursesContent, true, flags: JSON_THROW_ON_ERROR)['totalElements'];
        } catch (JsonException $exception) {
            throw CourseRetrievalFailedException::createWithJsonDecodeException($exception);
        }

        $pages = (int)ceil($totalCourses / self::COURSE_ENDPOINT_LIMIT);
        $requestDatasets = [];
        for ($page = 0; $page < $pages; $page++) {
            $requestDatasets[] = RequestData::create(
                'POST',
                self::COURSE_ENDPOINT_URI,
                ['page' => $page, 'size' => self::COURSE_ENDPOINT_LIMIT],
                ['semesterUri' => $semester->getUri()]
            );
        }

        $onFailureCallback = function (int $statusCode): void {
            throw CourseRetrievalFailedException::createWithHttpStatusCode($statusCode);
        };
        $onSuccessCallback = function (string $content) use ($semester): void {
            try {
                $pagedCoursesData = json_decode($content, true, flags: JSON_THROW_ON_ERROR);
            } catch (JsonException $exception) {
                throw CourseRetrievalFailedException::createWithJsonDecodeException($exception);
            }
            foreach ($pagedCoursesData['content'] as $courseData) {
                $course = Course::create($courseData, $semester);
                $this->dataManager->persistCourse($course);
            }
        };

        return $this->dataClient->fetchAllDataAsync($requestDatasets, $onFailureCallback, $onSuccessCallback);
    }

    /**
     * @return Promise
     * @throws AuthenticationException
     */
    private function fetchCurrentPerson(): Promise
    {
        $onFailureCallback = function (int $statusCode): void {
            throw PersonRetrievalFailedException::createWithHttpStatusCode($statusCode);
        };
        $onSuccessCallback = function (string $content): void {
            try {
                $personData = json_decode($content, true, flags: JSON_THROW_ON_ERROR);
            } catch (JsonException $exception) {
                throw PersonRetrievalFailedException::createWithJsonDecodeException($exception);
            }

            $person = Person::create($personData, true);
            $this->dataManager->persistPerson($person);
        };

        return $this->dataClient->fetchDataAsync(
            new RequestData('GET', self::CURRENT_PERSON_ENDPOINT_URI),
            $onFailureCallback,
            $onSuccessCallback
        );
    }

    /**
     * @param Person $me
     * @return Promise[]
     * @throws AuthenticationException
     */
    private function fetchMyStudies(Person $me): array
    {
        $requestData = RequestData::create('POST', self::MY_STUDIES_ENDPOINT_URI, json: ['personUri' => $me->getUri()]);
        $myStudiesContent = $this->dataClient->fetchDataSync($requestData);
        try {
            $myStudiesData = json_decode($myStudiesContent, true, flags: JSON_THROW_ON_ERROR);
        } catch (JsonException $exception) {
            throw MyStudiesRetrievalFailedException::createWithJsonDecodeException($exception);
        }

        $requestDatasets = [];
        foreach ($myStudiesData as $studyData) {
            $studyId = $studyData['id'];
            $requestDatasets[] = new RequestData('GET', self::STUDY_ENDPOINT_URI . $studyId);
        }

        $onFailureCallback = function (int $statusCode): void {
            throw StudyRetrievalFailedException::createWithHttpStatusCode($statusCode);
        };
        $onSuccessCallback = function (string $content): void {
            try {
                $studyData = json_decode($content, true, flags: JSON_THROW_ON_ERROR);
            } catch (JsonException $exception) {
                throw StudyRetrievalFailedException::createWithJsonDecodeException($exception);
            }

            $study = Study::create($studyData);
            $this->dataManager->persistStudy($study);
        };

        return $this->dataClient->fetchAllDataAsync($requestDatasets, $onFailureCallback, $onSuccessCallback);
    }

    /**
     * @param int $semesterId
     * @return Promise
     * @throws AuthenticationException
     */
    private function fetchSemester(int $semesterId): Promise
    {
        $onFailureCallback = function (int $statusCode): void {
            throw SemesterRetrievalFailedException::createWithHttpStatusCode($statusCode);
        };
        $onSuccessCallback = function (string $content): void {
            try {
                $semesterData = json_decode($content, true, flags: JSON_THROW_ON_ERROR);
            } catch (JsonException $exception) {
                throw SemesterRetrievalFailedException::createWithJsonDecodeException($exception);
            }

            $semester = Semester::create($semesterData);
            $this->dataManager->persistSemester($semester);
        };

        return $this->dataClient->fetchDataAsync(
            new RequestData('GET', self::SEMESTER_ENDPOINT_URI . $semesterId),
            $onFailureCallback,
            $onSuccessCallback
        );
    }
}
