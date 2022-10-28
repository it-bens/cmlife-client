<?php

declare(strict_types=1);

namespace ITB\CmlifeClient\Model;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
final class Person
{
    /**
     * @param string $uri
     * @param string $username
     * @param bool $isMe
     */
    public function __construct(
        #[ORM\Id]
        #[ORM\Column(name: 'uri', type: 'string')]
        private readonly string $uri,
        #[ORM\Column(name: 'username', type: 'string')]
        private readonly string $username,
        #[ORM\Column(name: 'is_me', type: 'boolean')]
        private readonly bool $isMe
    ) {
    }

    /**
     * @param array<string, mixed> $personData
     * @param bool $isMe
     * @return Person
     */
    public static function create(array $personData, bool $isMe): Person
    {
        $uri = $personData['uri'];
        $username = $personData['preferredUsername'];

        return new self($uri, $username, $isMe);
    }

    /**
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return bool
     */
    public function isMe(): bool
    {
        return $this->isMe;
    }
}
