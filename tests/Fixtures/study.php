<?php

/**
 * @return array<string, mixed>
 */
function getStudyData(): array
{
    return [
        'id' => 222222,
        'degrees' => [],
        'person' => [
            'id' => 1111111111,
            'code' => 'ERGMRHIRTJNGHRUJTNBTREGOHM',
            'firstName' => 'Max',
            'lastName' => 'Mustermann',
            'placeholder' => false,
            'enrolmentNumber' => '1234567',
            'uri' => '//ubt@cmorg/api/persons/1111111111',
        ],
        'numberOfSemesters' => 42,
        'curriculum' =>
            [
                'id' => 2002,
                'name' => '20. Juni 2012 ÄS 05. September 2019',
                'code' => '0612/0919',
                'nominalCredits' => 180,
                'standardSemesters' => 6,
                'assessmentTransferAllowed' => false,
                'root' =>
                    [
                        'id' => 543668,
                        'nameDe' => '20. Juni 2012 ÄS 05. September 2019',
                        'code' => '0612/0919',
                        'nominalCredits' => 180.0,
                        'weight' => 1.0,
                        'order' => 19,
                        'priority' => 'LAST',
                        'semesterPattern' => ['id' => 10000, 'uri' => '//ubt@cmco/api/semesterPattern/10000',],
                        'children' => [
                            [
                                'id' => 575741,
                                'nameDe' => 'Informatik (Wahlpflichtmodule] ',
                                'code' => 'A',
                                'weight' => 1.0,
                                'order' => 20,
                                'priority' => 'LAST',
                                'semesterPattern' => ['id' => 10000, 'uri' => '//ubt@cmco/api/semesterPattern/10000',],
                                'children' => [
                                    [
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
                                    ],
                                ],
                                'type' => 'RULE',
                                'valid' => true,
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
                                'uri' => '//ubt@cmco/api/nodes/575741',
                            ],
                        ],
                        'type' => 'ROOT',
                        'valid' => true,
                        'requirement' => false,
                        'courseEquivalenceUri' => '//ubt@cmco/api/courseEquivalences/155422',
                        'uri' => '//ubt@cmco/api/nodes/543668',
                    ],
                'studyPlanUrl' => 'https://campusonline.uni-bayreuth.de/ubto/wbSPO.downloadStudienVerlaufsplanPub?pStpStpNr=2002&PVERLAUFSPLANDOCNR=1351777',
                'curriculumDocumentUrl' => 'https://www.amtliche-bekanntmachungen.uni-bayreuth.de/de/amtliche-bekanntmachungen/konsolidierteFassungen/2019/2019-057-kF.pdf',
                'uri' => '//ubt@cmco/api/curricula/2002',
            ],
        'program' => [
            'id' => 234,
            'degreeGoal' => [
                'id' => 41,
                'nameDe' => 'Bachelor of Disaster',
                'nameEn' => 'Bachelor of Disaster',
                'shortName' => 'B.D.',
                'bolognaCompliant' => true,
                'uri' => '//ubt@cmco/api/degreeGoals/42',
            ],
            'subject' => [
                'id' => 1234,
                'nameDe' => 'Angewandte Chaoswissenschaft',
                'nameEn' => 'Applied Chaos Science',
                'uri' => '//ubt@cmco/api/subjects/1234',
            ],
            'type' => 'SINGLE',
            'children' => [],
            'uri' => '//ubt@cmco/api/programs/234',
        ],
        'state' => [
            'id' => 1,
            'code' => 'I',
            'nameDe' => 'rückgemeldet',
            'nameEn' => 'reregistered',
            'active' => true,
            'closed' => false,
            'uri' => '//ubt@cmco/api/studyStates/1',
        ],
        'studySemesters' => [
            [
                'id' => 1111111,
                'value' => 5,
                'semester' => [
                    'id' => 243,
                    'name' => 'Wintersemester 2022/23',
                    'shortName' => 'W 2022/23',
                    'type' => 'WINTER',
                    'orderValue' => 151,
                    'begin' => '2022-10-01',
                    'end' => '2023-03-31',
                    'lectureBegin' => '2022-10-17',
                    'lectureEnd' => '2023-02-10',
                    'uri' => '//ubt@cmco/api/semesters/243',
                ],
            ],
            [
                'id' => 2222222,
                'value' => 2,
                'semester' => [
                    'id' => 240,
                    'name' => 'Sommersemester 2021',
                    'shortName' => 'S 2021',
                    'type' => 'SUMMER',
                    'orderValue' => 148,
                    'begin' => '2021-04-01',
                    'end' => '2021-09-30',
                    'lectureBegin' => '2021-04-12',
                    'lectureEnd' => '2021-07-16',
                    'uri' => '//ubt@cmco/api/semesters/240',
                ],
            ],
            [
                'id' => 3333333,
                'value' => 1,
                'semester' => [
                    'id' => 239,
                    'name' => 'Wintersemester 2020/21',
                    'shortName' => 'W 2020/21',
                    'type' => 'WINTER',
                    'orderValue' => 147,
                    'begin' => '2020-10-01',
                    'end' => '2021-03-31',
                    'lectureBegin' => '2020-11-02',
                    'lectureEnd' => '2021-02-12',
                    'uri' => '//ubt@cmco/api/semesters/239',
                ],
            ],
            [
                'id' => 4444444,
                'value' => 3,
                'semester' => [
                    'id' => 241,
                    'name' => 'Wintersemester 2021/22',
                    'shortName' => 'W 2021/22',
                    'type' => 'WINTER',
                    'orderValue' => 149,
                    'begin' => '2021-10-01',
                    'end' => '2022-03-31',
                    'lectureBegin' => '2021-10-18',
                    'lectureEnd' => '2022-02-11',
                    'uri' => '//ubt@cmco/api/semesters/241',
                ],
            ],
            [
                'id' => 5555555,
                'value' => 4,
                'semester' => [
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
            ],
        ],
        'releasedAssessments' => [],
        'courseRegistrationCount' => 0,
        'examRegistrationCount' => 0,
        'uri' => '//ubt@cmco/api/studies/222222',
    ];
}

/**
 * @return array<string, mixed>
 */
function getRootNodeDataFromCurriculum(): array
{
    return getCurriculumData()['root'];
}

/**
 * @return array<string, mixed>
 */
function getRootNodeUpdateDataWithIdenticalNodes(): array
{
    $data = getRootNodeDataFromCurriculum();
    $data['nameDe'] = 'Geänderter Name 3';
    $data['children'][0]['nameDe'] = 'Geänderter Name 1';
    $data['children'][0]['children'][0]['nameDe'] = 'Geänderter Name 2';
    $data['children'][0]['children'][0]['children'][0]['nameDe'] = 'Geänderter Name 3';

    return $data;
}

/**
 * @return array<string, mixed>
 */
function getRootNodeUpdateDataWithRemovedChildOnSecondLevel(): array
{
    $data = getRootNodeDataFromCurriculum();
    $data['children'][0]['children'] = [];

    return $data;
}

/**
 * @return array<string, mixed>
 */
function getRootNodeUpdateDataWithAddedChildOnSecondLevel(): array
{
    $data = getRootNodeDataFromCurriculum();

    include_once __DIR__ . '/node/link_node.php';
    $data['children'][0]['children'][] = getLinkNodeData();

    return $data;
}

/**
 * @return array<string, mixed>
 */
function getCurriculumData(): array
{
    return getStudyData()['curriculum'];
}

/**
 * @return array<string, mixed>
 */
function getCurriculumDataWithWrongRootNodeType(): array
{
    $data = getStudyData()['curriculum'];
    $data['root']['type'] = 'LINK';

    return $data;
}

/**
 * @return array<string, mixed>
 */
function getStudyUpdateData(): array
{
    $data = getStudyData();
    $data['program']['subject']['nameDe'] = 'Angewandte finstere Chaoswissenschaft';
    $data['program']['degreeGoal']['nameDe'] = 'Bachelor of Sinister Disaster';
    $data['curriculum']['curriculumDocumentUrl'] = 'https://666.666.666.666';

    return $data;
}

/**
 * @return array<string, mixed>
 */
function getCurriculumUpdateData(): array
{
    $data = getCurriculumData();
    $data['studyPlanUrl'] = 'https://666.666.666.666';
    $data['curriculumDocumentUrl'] = 'https://777.777.777.777';
    $data['root']['nameDe'] = '66.06.666';

    return $data;
}
