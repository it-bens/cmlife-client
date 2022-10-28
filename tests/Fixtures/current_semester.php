<?php

/**
 * @return array<string, mixed>
 */
function getCurrentSemesterData(): array
{
    return [
        'id' => 243,
        'name' => 'Wintersemester 2022/23',
        'code' => '2022W',
        'shortName' => 'W 2022/23',
        'type' => 'WINTER',
        'orderValue' => 151,
        'begin' => '2022-10-01',
        'end' => '2023-03-31',
        'lectureBegin' => '2022-10-17',
        'lectureEnd' => '2023-02-10',
        'current' => true,
        'nextSemesterId' => 244,
        'previousSemesterUri' => '//ubt@cmco/api/semesters/242',
        'nextSemesterUri' => '//ubt@cmco/api/semesters/244',
        'uri' => '//ubt@cmco/api/semesters/243',
    ];
}
