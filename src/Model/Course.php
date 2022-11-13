<?php

declare(strict_types=1);

namespace ITB\CmlifeClient\Model;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
final class Course
{
    public function __construct(
        #[ORM\Id]
        #[ORM\Column(name: 'id', type: 'integer')]
        private readonly int $id,
        #[ORM\Column(name: 'uri', type: 'string')]
        private readonly string $uri,
        #[ORM\Column(name: 'equivalence_uri', type: 'string')]
        private readonly string $equivalenceUri,
        #[ORM\Column(name: 'name', type: 'string')]
        private string $name,
        #[ORM\Column(name: 'code', type: 'string')]
        private string $code,
        #[ORM\Column(name: 'frontend_url', type: 'string')]
        private string $frontendUrl,
        #[ORM\ManyToOne(targetEntity: Semester::class)]
        #[ORM\JoinColumn(name: 'semester_id', referencedColumnName: 'id')]
        private readonly Semester $semester
    ) {
    }

    /**
     * @param array<string, mixed> $courseData
     * @param Semester $semester
     * @return Course
     */
    public static function create(array $courseData, Semester $semester): Course
    {
        $id = $courseData['id'];
        $uri = $courseData['uri'];
        $equivalenceUri = $courseData['equivalenceUri'];

        $name = $courseData['nameDe'];
        $code = $courseData['code'];

        $frontendUrl = $courseData['frontendUrl'];

        return new self($id, $uri, $equivalenceUri, $name, $code, $frontendUrl, $semester);
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getEquivalenceUri(): string
    {
        return $this->equivalenceUri;
    }

    /**
     * @return string
     */
    public function getFrontendUrl(): string
    {
        return $this->frontendUrl;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return Semester
     */
    public function getSemester(): Semester
    {
        return $this->semester;
    }

    /**
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * @param Course $course
     * @return void
     */
    public function update(Course $course): void
    {
        $this->name = $course->name;
        $this->code = $course->code;
        $this->frontendUrl = $course->frontendUrl;
    }
}
