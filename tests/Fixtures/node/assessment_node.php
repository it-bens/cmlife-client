<?php

/**
 * @return array<string, mixed>
 */
function getAssessmentNodeData(): array
{
    return [
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
    ];
}
