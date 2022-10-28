<?php

/**
 * @return array<string, mixed>
 */
function getOfferNodeData(): array
{
    return [
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
    ];
}
