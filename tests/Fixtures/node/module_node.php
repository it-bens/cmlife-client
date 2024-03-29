<?php

/**
 * @return array<string, mixed>
 */
function getModuleNodeData(): array
{
    return [
        'id' => 575821,
        'nameDe' => 'Computersehen',
        'nameEn' => 'Computer vision',
        'code' => 'Fak110692',
        'nominalCredits' => 5.0,
        'weight' => 1.0,
        'order' => 100,
        'priority' => 'LAST',
        'semesterPattern' => ['id' => 10000, 'uri' => '//ubt@cmco/api/semesterPattern/10000',],
        'children' => [
            [
                'id' => 575822,
                'nameDe' => 'Computersehen',
                'code' => 'INF 208',
                'nominalCredits' => 5.0,
                'weight' => 1.0,
                'order' => 10,
                'priority' => 'BEST',
                'semesterPattern' => ['id' => 10000, 'uri' => '//ubt@cmco/api/semesterPattern/10000',],
                'children' => [
                    [
                        'id' => 575823,
                        'weight' => 1.0,
                        'priority' => 'BEST',
                        'semesterPattern' => ['id' => 10000, 'uri' => '//ubt@cmco/api/semesterPattern/10000',],
                        'children' => [],
                        'type' => 'LINK',
                        'valid' => true,
                        'organization' => [
                            'id' => 14336,
                            'nameDe' => 'Lehrstuhl Angewandte Informatik III (Henrich]',
                            'nameEn' => 'Lehrstuhl Angewandte Informatik III (Henrich]',
                            'uri' => '//ubt@cmorg/api/organizations/14336',
                        ],
                        'requirement' => false,
                        'courseEquivalenceUri' => '//ubt@cmco/api/courseEquivalences/87036',
                        'templateUri' => '//ubt@cmco/api/nodes/34039',
                        'uri' => '//ubt@cmco/api/nodes/575823',
                    ],
                    [
                        'id' => 575824,
                        'weight' => 1.0,
                        'priority' => 'BEST',
                        'semesterPattern' => ['id' => 10000, 'uri' => '//ubt@cmco/api/semesterPattern/10000',],
                        'children' => [],
                        'type' => 'LINK',
                        'valid' => true,
                        'organization' => [
                            'id' => 14336,
                            'nameDe' => 'Lehrstuhl Angewandte Informatik III (Henrich]',
                            'nameEn' => 'Lehrstuhl Angewandte Informatik III (Henrich]',
                            'uri' => '//ubt@cmorg/api/organizations/14336',
                        ],
                        'requirement' => false,
                        'courseEquivalenceUri' => '//ubt@cmco/api/courseEquivalences/117037',
                        'templateUri' => '//ubt@cmco/api/nodes/191903',
                        'uri' => '//ubt@cmco/api/nodes/575824',
                    ],
                ],
                'type' => 'ASSESSMENT',
                'valid' => true,
                'organization' => [
                    'id' => 14336,
                    'nameDe' => 'Lehrstuhl Angewandte Informatik III (Henrich]',
                    'nameEn' => 'Lehrstuhl Angewandte Informatik III (Henrich]',
                    'uri' => '//ubt@cmorg/api/organizations/14336',
                ],
                'requirement' => false,
                'courseEquivalenceUri' => '//ubt@cmco/api/courseEquivalences/121093',
                'templateUri' => '//ubt@cmco/api/nodes/19085',
                'uri' => '//ubt@cmco/api/nodes/575822',
            ],
            [
                'id' => 575825,
                'nameDe' => 'Übung Computersehen',
                'weight' => 1.0,
                'priority' => 'LAST',
                'semesterPattern' => ['id' => 10000, 'uri' => '//ubt@cmco/api/semesterPattern/10000',],
                'children' => [
                    [
                        'id' => 575826,
                        'weight' => 1.0,
                        'priority' => 'LAST',
                        'semesterPattern' => ['id' => 10000, 'uri' => '//ubt@cmco/api/semesterPattern/10000',],
                        'children' => [],
                        'type' => 'LINK',
                        'valid' => true,
                        'organization' => [
                            'id' => 14336,
                            'nameDe' => 'Lehrstuhl Angewandte Informatik III (Henrich]',
                            'nameEn' => 'Lehrstuhl Angewandte Informatik III (Henrich]',
                            'uri' => '//ubt@cmorg/api/organizations/14336',
                        ],
                        'requirement' => false,
                        'courseEquivalenceUri' => '//ubt@cmco/api/courseEquivalences/87042',
                        'templateUri' => '//ubt@cmco/api/nodes/35345',
                        'uri' => '//ubt@cmco/api/nodes/575826',
                    ],
                ],
                'type' => 'OFFER',
                'valid' => true,
                'organization' => [
                    'id' => 14336,
                    'nameDe' => 'Lehrstuhl Angewandte Informatik III (Henrich]',
                    'nameEn' => 'Lehrstuhl Angewandte Informatik III (Henrich]',
                    'uri' => '//ubt@cmorg/api/organizations/14336',
                ],
                'requirement' => false,
                'templateUri' => '//ubt@cmco/api/nodes/20347',
                'uri' => '//ubt@cmco/api/nodes/575825',
            ],
        ],
        'type' => 'MODULE',
        'valid' => true,
        'organization' => [
            'id' => 14336,
            'nameDe' => 'Lehrstuhl Angewandte Informatik III (Henrich]',
            'nameEn' => 'Lehrstuhl Angewandte Informatik III (Henrich]',
            'uri' => '//ubt@cmorg/api/organizations/14336',
        ],
        'requirement' => false,
        'lastOfferedIn' => [
            'id' => 242,
            'name' => 'Sommersemester 2022',
            'shortName' => 'S 2022',
            'type' => 'SUMMER',
            'orderValue' => 150,
            'begin' => '2022-04-01',
            'end' => '2022-09-30',
            'lectureBegin' => '2022-04-25',
            'lectureEnd' => '2022-07-29',
            'uri' => '//ubt@cmco/api/semesters/242',
        ],
        'templateUri' => '//ubt@cmco/api/nodes/5194',
        'uri' => '//ubt@cmco/api/nodes/575821',
    ];
}
