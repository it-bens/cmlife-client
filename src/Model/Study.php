<?php

declare(strict_types=1);

namespace ITB\CmlifeClient\Model;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
final class Study
{
    /**
     * @param int $id
     * @param string $uri
     * @param string $programSubject
     * @param string $programDegree
     * @param Curriculum $curriculum
     */
    public function __construct(
        #[ORM\Id]
        #[ORM\Column(name: 'id', type: 'integer')]
        private readonly int $id,
        #[ORM\Column(name: 'uri', type: 'string')]
        private readonly string $uri,
        #[ORM\Column(name: 'program_subject', type: 'string')]
        private readonly string $programSubject,
        #[ORM\Column(name: 'program_degree', type: 'string')]
        private readonly string $programDegree,
        #[ORM\OneToOne(targetEntity: Curriculum::class, cascade: ['all'], orphanRemoval: true)]
        #[ORM\JoinColumn(name: 'curriculum_id', referencedColumnName: 'id')]
        private readonly Curriculum $curriculum
    ) {
    }

    /**
     * @param array<string, mixed> $studyData
     * @return Study
     */
    public static function create(array $studyData): Study
    {
        $id = $studyData['id'];
        $uri = $studyData['uri'];
        $programSubject = $studyData['program']['subject']['nameDe'];
        $programDegree = $studyData['program']['degreeGoal']['nameDe'];

        $curriculum = Curriculum::create($studyData['curriculum']);

        return new self($id, $uri, $programSubject, $programDegree, $curriculum);
    }

    /**
     * @return Curriculum
     */
    public function getCurriculum(): Curriculum
    {
        return $this->curriculum;
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
    public function getProgramDegree(): string
    {
        return $this->programDegree;
    }

    /**
     * @return string
     */
    public function getProgramSubject(): string
    {
        return $this->programSubject;
    }

    /**
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }
}
