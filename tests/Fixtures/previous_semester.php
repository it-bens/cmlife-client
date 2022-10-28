<?php

/**
 * @return array<string, mixed>
 */
function getPreviousSemesterData(): array
{
    return [
        'id' => 242,
        'name' => 'Sommersemester 2022',
        'code' => '2022S',
        'shortName' => 'S 2022',
        'type' => 'SUMMER',
        'orderValue' => 150,
        'begin' => '2022-04-01',
        'end' => '2022-09-30',
        'lectureBegin' => '2022-04-25',
        'lectureEnd' => '2022-07-29',
        'current' => false,
        'nextSemesterId' => 243,
        'previousSemesterUri' => '//ubt@cmco/api/semesters/241',
        'nextSemesterUri' => '//ubt@cmco/api/semesters/243',
        'uri' => '//ubt@cmco/api/semesters/242',
    ];
}
