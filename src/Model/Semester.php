<?php

declare(strict_types=1);

namespace ITB\CmlifeClient\Model;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
final class Semester
{
    /**
     * @param int $id
     * @param string $uri
     * @param string $name
     * @param bool $isCurrent
     */
    public function __construct(
        #[ORM\Id]
        #[ORM\Column(name: 'id', type: 'integer')]
        private readonly int $id,
        #[ORM\Column(name: 'uri', type: 'string')]
        private readonly string $uri,
        #[ORM\Column(name: 'name', type: 'string')]
        private readonly string $name,
        #[ORM\Column(name: 'is_current', type: 'boolean')]
        private readonly bool $isCurrent
    ) {
    }

    /**
     * @param array<string, mixed> $semesterData
     * @return Semester
     */
    public static function create(array $semesterData): Semester
    {
        $id = $semesterData['id'];
        $uri = $semesterData['uri'];
        $name = $semesterData['name'];
        $isCurrent = $semesterData['current'];

        return new self($id, $uri, $name, $isCurrent);
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
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * @return bool
     */
    public function isCurrent(): bool
    {
        return $this->isCurrent;
    }
}
