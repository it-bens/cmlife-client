<?php

declare(strict_types=1);

namespace ITB\CmlifeClient\Tests\Model;

use Generator;
use ITB\CmlifeClient\Model\Person;
use PHPUnit\Framework\TestCase;

final class PersonTest extends TestCase
{
    /**
     * @return Generator
     */
    public function provideForTestCreatePerson(): Generator
    {
        include_once __DIR__ . '/../Fixtures/me.php';
        include_once __DIR__ . '/../Fixtures/not_me.php';

        yield 'me' => [getPersonMeData(), true];
        yield 'not me' => [getPersonNotMeData(), false];
    }

    /**
     * @return Generator
     */
    public function provideForTestGetUri(): Generator
    {
        include_once __DIR__ . '/../Fixtures/me.php';
        $me = Person::create(getPersonMeData(), true);
        include_once __DIR__ . '/../Fixtures/not_me.php';
        $notMe = Person::create(getPersonNotMeData(), false);

        yield 'me' => [$me, getPersonMeData()['uri']];
        yield 'not me' => [$notMe, getPersonNotMeData()['uri']];
    }

    /**
     * @return Generator
     */
    public function provideForTestGetUsername(): Generator
    {
        include_once __DIR__ . '/../Fixtures/me.php';
        $me = Person::create(getPersonMeData(), true);
        include_once __DIR__ . '/../Fixtures/not_me.php';
        $notMe = Person::create(getPersonNotMeData(), false);

        yield 'me' => [$me, getPersonMeData()['preferredUsername']];
        yield 'not me' => [$notMe, getPersonNotMeData()['preferredUsername']];
    }

    /**
     * @return Generator
     */
    public function provideForTestIsMe(): Generator
    {
        include_once __DIR__ . '/../Fixtures/me.php';
        $me = Person::create(getPersonMeData(), true);
        include_once __DIR__ . '/../Fixtures/not_me.php';
        $notMe = Person::create(getPersonNotMeData(), false);

        yield 'me' => [$me, true];
        yield 'not me' => [$notMe, false];
    }

    /**
     * @dataProvider provideForTestCreatePerson
     *
     * @param array<string, mixed> $personData
     * @param bool $isMe
     * @return void
     */
    public function testCreatePerson(array $personData, bool $isMe): void
    {
        $person = Person::create($personData, $isMe);

        $this->assertInstanceOf(Person::class, $person);
    }

    /**
     * @dataProvider provideForTestIsMe
     *
     * @param Person $person
     * @param bool $expectedIsMe
     * @return void
     */
    public function testGetIsMe(Person $person, bool $expectedIsMe): void
    {
        $this->assertEquals($expectedIsMe, $person->isMe());
    }

    /**
     * @dataProvider provideForTestGetUri
     *
     * @param Person $person
     * @param string $expectedUri
     * @return void
     */
    public function testGetUri(Person $person, string $expectedUri): void
    {
        $this->assertEquals($expectedUri, $person->getUri());
    }

    /**
     * @dataProvider provideForTestGetUsername
     *
     * @param Person $person
     * @param string $expectedUsername
     * @return void
     */
    public function testGetUsername(Person $person, string $expectedUsername): void
    {
        $this->assertEquals($expectedUsername, $person->getUsername());
    }
}
