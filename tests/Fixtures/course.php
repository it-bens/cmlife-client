<?php

/**
 * @return array<string, mixed>
 */
function getCourseData(): array
{
    return [
        'id' => 308609,
        'nameDe' => 'Aktuelle Themen der Umweltgeochemie',
        'weeklyHoursPerSemester' => 2.0,
        'code' => '28021',
        'semester' => [
            'id' => 243,
            'name' => 'Wintersemester 2022/23',
            'orderValue' => 151,
            'begin' => '2022-10-01',
            'end' => '2023-03-31',
            'uri' => '//ubt@cmco/api/semesters/243',
        ],
        'type' => [
            'id' => 41,
            'nameDe' => 'Kurs',
            'nameEn' => 'Course',
            'code' => '-',
            'real' => true,
            'placeholder' => false,
            'uri' => '//ubt@cmco/api/courseTypes/41',
        ],
        'managingOrganization' => ['id' => 14487, 'uri' => '//ubt@cmorg/api/organizations/14487',],
        'persons' => [
            [
                'id' => 51822,
                'code' => 'D732FFCA9D15C8D0',
                'firstName' => 'Britta',
                'lastName' => 'Planer-Friedrich',
                'gender' => 'FEMALE',
                'placeholder' => false,
                'title' => 'Univ.-Prof. Dr.',
                'courseCount' => 7,
                'uri' => '//ubt@cmorg/api/persons/51822',
            ],
        ],
        'bookmarked' => false,
        'registrationActive' => false,
        'unregistrationActive' => false,
        'examRegistrationActive' => false,
        'examUnregistrationActive' => false,
        'nextEvent' => [
            'id' => 1645809,
            'state' => 'CONFIRMED',
            'from' => '2022-11-09T08:00:00',
            'to' => '2022-11-09T10:00:00',
            'room' => ['id' => 1119, 'name' => 'kein Raum benötigt', 'real' => false, 'uri' => '//ubt@cmco/api/rooms/1119',],
            'information' => 'Besprechungsraum GEO III',
            'type' => 'ACTUAL',
            'uri' => '//ubt@cmco/api/events/1645809',
        ],
        'languages' => [
            [
                'language' => ['id' => 2, 'code' => 'EN', 'nameDe' => 'Englisch', 'nameEn' => 'English', 'uri' => '//ubt@cmco/api/languages/2',],
                'mainLanguage' => true,
            ],
        ],
        'linkedInAnyStudy' => false,
        'equivalenceUri' => '//ubt@cmco/api/courseEquivalences/85516',
        'frontendUrl' => 'https://my.uni-bayreuth.de/cmlife/s/courses/Ly91YnRAY21jby9hcGkvY291cnNlcy8zMDg2MDk=',
        'uri' => '//ubt@cmco/api/courses/308609',
    ];
}

/**
 * @return array<string, mixed>
 */
function getCourseUpdateData(): array
{
    $data = getCourseData();
    $data['nameDe'] = 'Vergangene Themen der Umweltgeochemie';
    $data['code'] = '1337';
    $data['frontendUrl'] = 'https://666.666.666.666';

    return $data;
}
