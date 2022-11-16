<?php

/**
 * @return array<string, mixed>
 */
function getFocusNodeData(): array
{
    return  [
        "id" => 128389,
        "nameDe" => "Lebensmittel, Gesundheit und Wirtschaft",
        "code" => "X.-alt",
        "weight" => 1,
        "order" => 3100,
        "priority" => "LAST",
        "semesterPattern" => [
            "id" => 10000,
            "uri" => "//ubt@cmco/api/semesterPattern/10000"
        ],
        "children" => [
            [
                "id" => 128390,
                "nameDe" => "studienabschließende Klausur",
                "code" => "LGW-SK",
                "weight" => 4,
                "order" => 20,
                "priority" => "LAST",
                "semesterPattern" => [
                    "id" => 10000,
                    "uri" => "//ubt@cmco/api/semesterPattern/10000"
                ],
                "children" => [
                    [
                        "id" => 128391,
                        "nameDe" => "studienabschließende Klausur",
                        "weight" => 4,
                        "order" => 10,
                        "priority" => "BEST",
                        "semesterPattern" => [
                            "id" => 7,
                            "uri" => "//ubt@cmco/api/semesterPattern/7"
                        ],
                        "children" => [
                            [
                                "id" => 129303,
                                "weight" => 1,
                                "priority" => "LAST",
                                "semesterPattern" => [
                                    "id" => 10000,
                                    "uri" => "//ubt@cmco/api/semesterPattern/10000"
                                ],
                                "children" => [
                                ],
                                "type" => "LINK",
                                "valid" => true,
                                "requirement" => false,
                                "courseEquivalenceUri" => "//ubt@cmco/api/courseEquivalences/124232",
                                "uri" => "//ubt@cmco/api/nodes/129303"
                            ]
                        ],
                        "type" => "ASSESSMENT",
                        "valid" => true,
                        "requirement" => false,
                        "courseEquivalenceUri" => "//ubt@cmco/api/courseEquivalences/124022",
                        "uri" => "//ubt@cmco/api/nodes/128391"
                    ]
                ],
                "type" => "MODULE",
                "valid" => true,
                "requirement" => false,
                "lastOfferedIn" => [
                    "id" => 243,
                    "name" => "Wintersemester 2022/23",
                    "shortName" => "W 2022/23",
                    "type" => "WINTER",
                    "orderValue" => 151,
                    "begin" => "2022-10-01",
                    "end" => "2023-03-31",
                    "lectureBegin" => "2022-10-17",
                    "lectureEnd" => "2023-02-10",
                    "uri" => "//ubt@cmco/api/semesters/243"
                ],
                "uri" => "//ubt@cmco/api/nodes/128390"
            ],
            [
                "id" => 128409,
                "nameDe" => "Studienbegleitende Prüfungsleistung im Schwerpunkt Lebensmittel, Gesundheit und Wirtschaft",
                "code" => "Fak315248",
                "weight" => 6,
                "order" => 20,
                "priority" => "LAST",
                "semesterPattern" => [
                    "id" => 10000,
                    "uri" => "//ubt@cmco/api/semesterPattern/10000"
                ],
                "children" => [
                    [
                        "id" => 128410,
                        "nameDe" => "Schriftliche Seminarleistung",
                        "weight" => 2,
                        "order" => 10,
                        "priority" => "BEST",
                        "semesterPattern" => [
                            "id" => 7,
                            "uri" => "//ubt@cmco/api/semesterPattern/7"
                        ],
                        "children" => [
                            [
                                "id" => 638606,
                                "weight" => 1,
                                "priority" => "LAST",
                                "semesterPattern" => [
                                    "id" => 10000,
                                    "uri" => "//ubt@cmco/api/semesterPattern/10000"
                                ],
                                "children" => [
                                ],
                                "type" => "LINK",
                                "valid" => true,
                                "organization" => [
                                    "id" => 14569,
                                    "nameDe" => "Rechtswissenschaften",
                                    "nameEn" => "Law",
                                    "uri" => "//ubt@cmorg/api/organizations/14569"
                                ],
                                "requirement" => false,
                                "courseEquivalenceUri" => "//ubt@cmco/api/courseEquivalences/146280",
                                "templateUri" => "//ubt@cmco/api/nodes/638605",
                                "uri" => "//ubt@cmco/api/nodes/638606"
                            ],
                            [
                                "id" => 355671,
                                "weight" => 1,
                                "priority" => "LAST",
                                "semesterPattern" => [
                                    "id" => 10000,
                                    "uri" => "//ubt@cmco/api/semesterPattern/10000"
                                ],
                                "children" => [
                                ],
                                "type" => "LINK",
                                "valid" => true,
                                "organization" => [
                                    "id" => 14569,
                                    "nameDe" => "Rechtswissenschaften",
                                    "nameEn" => "Law",
                                    "uri" => "//ubt@cmorg/api/organizations/14569"
                                ],
                                "requirement" => false,
                                "courseEquivalenceUri" => "//ubt@cmco/api/courseEquivalences/127365",
                                "templateUri" => "//ubt@cmco/api/nodes/355670",
                                "uri" => "//ubt@cmco/api/nodes/355671"
                            ],
                            [
                                "id" => 144288,
                                "weight" => 1,
                                "priority" => "LAST",
                                "semesterPattern" => [
                                    "id" => 10000,
                                    "uri" => "//ubt@cmco/api/semesterPattern/10000"
                                ],
                                "children" => [
                                ],
                                "type" => "LINK",
                                "valid" => true,
                                "organization" => [
                                    "id" => 14569,
                                    "nameDe" => "Rechtswissenschaften",
                                    "nameEn" => "Law",
                                    "uri" => "//ubt@cmorg/api/organizations/14569"
                                ],
                                "requirement" => false,
                                "courseEquivalenceUri" => "//ubt@cmco/api/courseEquivalences/125134",
                                "templateUri" => "//ubt@cmco/api/nodes/144287",
                                "uri" => "//ubt@cmco/api/nodes/144288"
                            ],
                            [
                                "id" => 152977,
                                "weight" => 1,
                                "priority" => "LAST",
                                "semesterPattern" => [
                                    "id" => 10000,
                                    "uri" => "//ubt@cmco/api/semesterPattern/10000"
                                ],
                                "children" => [
                                ],
                                "type" => "LINK",
                                "valid" => true,
                                "organization" => [
                                    "id" => 14569,
                                    "nameDe" => "Rechtswissenschaften",
                                    "nameEn" => "Law",
                                    "uri" => "//ubt@cmorg/api/organizations/14569"
                                ],
                                "requirement" => false,
                                "courseEquivalenceUri" => "//ubt@cmco/api/courseEquivalences/125524",
                                "templateUri" => "//ubt@cmco/api/nodes/152976",
                                "uri" => "//ubt@cmco/api/nodes/152977"
                            ]
                        ],
                        "type" => "ASSESSMENT",
                        "valid" => true,
                        "organization" => [
                            "id" => 14569,
                            "nameDe" => "Rechtswissenschaften",
                            "nameEn" => "Law",
                            "uri" => "//ubt@cmorg/api/organizations/14569"
                        ],
                        "requirement" => false,
                        "courseEquivalenceUri" => "//ubt@cmco/api/courseEquivalences/148688",
                        "templateUri" => "//ubt@cmco/api/nodes/128387",
                        "uri" => "//ubt@cmco/api/nodes/128410"
                    ],
                    [
                        "id" => 128411,
                        "nameDe" => "Mündliche Seminarleistung",
                        "weight" => 1,
                        "order" => 20,
                        "priority" => "BEST",
                        "semesterPattern" => [
                            "id" => 7,
                            "uri" => "//ubt@cmco/api/semesterPattern/7"
                        ],
                        "children" => [
                            [
                                "id" => 136951,
                                "weight" => 1,
                                "priority" => "LAST",
                                "semesterPattern" => [
                                    "id" => 10000,
                                    "uri" => "//ubt@cmco/api/semesterPattern/10000"
                                ],
                                "children" => [
                                ],
                                "type" => "LINK",
                                "valid" => true,
                                "organization" => [
                                    "id" => 14569,
                                    "nameDe" => "Rechtswissenschaften",
                                    "nameEn" => "Law",
                                    "uri" => "//ubt@cmorg/api/organizations/14569"
                                ],
                                "requirement" => false,
                                "courseEquivalenceUri" => "//ubt@cmco/api/courseEquivalences/124673",
                                "templateUri" => "//ubt@cmco/api/nodes/136950",
                                "uri" => "//ubt@cmco/api/nodes/136951"
                            ]
                        ],
                        "type" => "ASSESSMENT",
                        "valid" => true,
                        "organization" => [
                            "id" => 14569,
                            "nameDe" => "Rechtswissenschaften",
                            "nameEn" => "Law",
                            "uri" => "//ubt@cmorg/api/organizations/14569"
                        ],
                        "requirement" => false,
                        "templateUri" => "//ubt@cmco/api/nodes/128388",
                        "uri" => "//ubt@cmco/api/nodes/128411"
                    ]
                ],
                "type" => "MODULE",
                "valid" => true,
                "organization" => [
                    "id" => 14569,
                    "nameDe" => "Rechtswissenschaften",
                    "nameEn" => "Law",
                    "uri" => "//ubt@cmorg/api/organizations/14569"
                ],
                "requirement" => false,
                "lastOfferedIn" => [
                    "id" => 243,
                    "name" => "Wintersemester 2022/23",
                    "shortName" => "W 2022/23",
                    "type" => "WINTER",
                    "orderValue" => 151,
                    "begin" => "2022-10-01",
                    "end" => "2023-03-31",
                    "lectureBegin" => "2022-10-17",
                    "lectureEnd" => "2023-02-10",
                    "uri" => "//ubt@cmco/api/semesters/243"
                ],
                "templateUri" => "//ubt@cmco/api/nodes/128386",
                "uri" => "//ubt@cmco/api/nodes/128409"
            ],
            [
                "id" => 128412,
                "nameDe" => "Veranstaltungen im Schwerpunkt Lebensmittel, Gesundheit und Wirtschaft",
                "code" => "Fak315247",
                "weight" => 0,
                "order" => 10,
                "priority" => "LAST",
                "semesterPattern" => [
                    "id" => 10000,
                    "uri" => "//ubt@cmco/api/semesterPattern/10000"
                ],
                "children" => [
                    [
                        "id" => 128413,
                        "nameDe" => "Pflichtveranstaltungen",
                        "weight" => 1,
                        "order" => 10,
                        "priority" => "LAST",
                        "semesterPattern" => [
                            "id" => 10000,
                            "uri" => "//ubt@cmco/api/semesterPattern/10000"
                        ],
                        "children" => [
                            [
                                "id" => 183563,
                                "weight" => 1,
                                "priority" => "LAST",
                                "semesterPattern" => [
                                    "id" => 10000,
                                    "uri" => "//ubt@cmco/api/semesterPattern/10000"
                                ],
                                "children" => [
                                ],
                                "type" => "LINK",
                                "valid" => true,
                                "organization" => [
                                    "id" => 14569,
                                    "nameDe" => "Rechtswissenschaften",
                                    "nameEn" => "Law",
                                    "uri" => "//ubt@cmorg/api/organizations/14569"
                                ],
                                "requirement" => false,
                                "courseEquivalenceUri" => "//ubt@cmco/api/courseEquivalences/125755",
                                "templateUri" => "//ubt@cmco/api/nodes/183562",
                                "uri" => "//ubt@cmco/api/nodes/183563"
                            ],
                            [
                                "id" => 719985,
                                "weight" => 1,
                                "priority" => "LAST",
                                "semesterPattern" => [
                                    "id" => 10000,
                                    "uri" => "//ubt@cmco/api/semesterPattern/10000"
                                ],
                                "children" => [
                                ],
                                "type" => "LINK",
                                "valid" => true,
                                "organization" => [
                                    "id" => 14569,
                                    "nameDe" => "Rechtswissenschaften",
                                    "nameEn" => "Law",
                                    "uri" => "//ubt@cmorg/api/organizations/14569"
                                ],
                                "requirement" => false,
                                "courseEquivalenceUri" => "//ubt@cmco/api/courseEquivalences/148201",
                                "uri" => "//ubt@cmco/api/nodes/719985"
                            ],
                            [
                                "id" => 719970,
                                "weight" => 1,
                                "priority" => "LAST",
                                "semesterPattern" => [
                                    "id" => 10000,
                                    "uri" => "//ubt@cmco/api/semesterPattern/10000"
                                ],
                                "children" => [
                                ],
                                "type" => "LINK",
                                "valid" => true,
                                "organization" => [
                                    "id" => 14569,
                                    "nameDe" => "Rechtswissenschaften",
                                    "nameEn" => "Law",
                                    "uri" => "//ubt@cmorg/api/organizations/14569"
                                ],
                                "requirement" => false,
                                "courseEquivalenceUri" => "//ubt@cmco/api/courseEquivalences/148200",
                                "uri" => "//ubt@cmco/api/nodes/719970"
                            ],
                            [
                                "id" => 128414,
                                "weight" => 1,
                                "priority" => "LAST",
                                "semesterPattern" => [
                                    "id" => 10000,
                                    "uri" => "//ubt@cmco/api/semesterPattern/10000"
                                ],
                                "children" => [
                                ],
                                "type" => "LINK",
                                "valid" => true,
                                "organization" => [
                                    "id" => 14569,
                                    "nameDe" => "Rechtswissenschaften",
                                    "nameEn" => "Law",
                                    "uri" => "//ubt@cmorg/api/organizations/14569"
                                ],
                                "requirement" => false,
                                "courseEquivalenceUri" => "//ubt@cmco/api/courseEquivalences/90526",
                                "templateUri" => "//ubt@cmco/api/nodes/128381",
                                "uri" => "//ubt@cmco/api/nodes/128414"
                            ],
                            [
                                "id" => 183567,
                                "weight" => 1,
                                "priority" => "LAST",
                                "semesterPattern" => [
                                    "id" => 10000,
                                    "uri" => "//ubt@cmco/api/semesterPattern/10000"
                                ],
                                "children" => [
                                ],
                                "type" => "LINK",
                                "valid" => true,
                                "organization" => [
                                    "id" => 14569,
                                    "nameDe" => "Rechtswissenschaften",
                                    "nameEn" => "Law",
                                    "uri" => "//ubt@cmorg/api/organizations/14569"
                                ],
                                "requirement" => false,
                                "courseEquivalenceUri" => "//ubt@cmco/api/courseEquivalences/122295",
                                "templateUri" => "//ubt@cmco/api/nodes/183566",
                                "uri" => "//ubt@cmco/api/nodes/183567"
                            ],
                            [
                                "id" => 183565,
                                "weight" => 1,
                                "priority" => "LAST",
                                "semesterPattern" => [
                                    "id" => 10000,
                                    "uri" => "//ubt@cmco/api/semesterPattern/10000"
                                ],
                                "children" => [
                                ],
                                "type" => "LINK",
                                "valid" => true,
                                "organization" => [
                                    "id" => 14569,
                                    "nameDe" => "Rechtswissenschaften",
                                    "nameEn" => "Law",
                                    "uri" => "//ubt@cmorg/api/organizations/14569"
                                ],
                                "requirement" => false,
                                "courseEquivalenceUri" => "//ubt@cmco/api/courseEquivalences/86072",
                                "templateUri" => "//ubt@cmco/api/nodes/183564",
                                "uri" => "//ubt@cmco/api/nodes/183565"
                            ],
                            [
                                "id" => 128416,
                                "weight" => 1,
                                "priority" => "LAST",
                                "semesterPattern" => [
                                    "id" => 10000,
                                    "uri" => "//ubt@cmco/api/semesterPattern/10000"
                                ],
                                "children" => [
                                ],
                                "type" => "LINK",
                                "valid" => true,
                                "organization" => [
                                    "id" => 14569,
                                    "nameDe" => "Rechtswissenschaften",
                                    "nameEn" => "Law",
                                    "uri" => "//ubt@cmorg/api/organizations/14569"
                                ],
                                "requirement" => false,
                                "courseEquivalenceUri" => "//ubt@cmco/api/courseEquivalences/122291",
                                "templateUri" => "//ubt@cmco/api/nodes/128383",
                                "uri" => "//ubt@cmco/api/nodes/128416"
                            ],
                            [
                                "id" => 128415,
                                "weight" => 1,
                                "priority" => "LAST",
                                "semesterPattern" => [
                                    "id" => 10000,
                                    "uri" => "//ubt@cmco/api/semesterPattern/10000"
                                ],
                                "children" => [
                                ],
                                "type" => "LINK",
                                "valid" => true,
                                "organization" => [
                                    "id" => 14569,
                                    "nameDe" => "Rechtswissenschaften",
                                    "nameEn" => "Law",
                                    "uri" => "//ubt@cmorg/api/organizations/14569"
                                ],
                                "requirement" => false,
                                "courseEquivalenceUri" => "//ubt@cmco/api/courseEquivalences/88633",
                                "templateUri" => "//ubt@cmco/api/nodes/128382",
                                "uri" => "//ubt@cmco/api/nodes/128415"
                            ],
                            [
                                "id" => 128417,
                                "weight" => 1,
                                "priority" => "LAST",
                                "semesterPattern" => [
                                    "id" => 10000,
                                    "uri" => "//ubt@cmco/api/semesterPattern/10000"
                                ],
                                "children" => [
                                ],
                                "type" => "LINK",
                                "valid" => true,
                                "organization" => [
                                    "id" => 14569,
                                    "nameDe" => "Rechtswissenschaften",
                                    "nameEn" => "Law",
                                    "uri" => "//ubt@cmorg/api/organizations/14569"
                                ],
                                "requirement" => false,
                                "courseEquivalenceUri" => "//ubt@cmco/api/courseEquivalences/122294",
                                "templateUri" => "//ubt@cmco/api/nodes/128384",
                                "uri" => "//ubt@cmco/api/nodes/128417"
                            ]
                        ],
                        "type" => "OFFER",
                        "valid" => true,
                        "organization" => [
                            "id" => 14569,
                            "nameDe" => "Rechtswissenschaften",
                            "nameEn" => "Law",
                            "uri" => "//ubt@cmorg/api/organizations/14569"
                        ],
                        "requirement" => false,
                        "templateUri" => "//ubt@cmco/api/nodes/128379",
                        "uri" => "//ubt@cmco/api/nodes/128413"
                    ],
                    [
                        "id" => 128418,
                        "nameDe" => "fakultative Veranstaltungen",
                        "weight" => 1,
                        "order" => 20,
                        "priority" => "LAST",
                        "semesterPattern" => [
                            "id" => 10000,
                            "uri" => "//ubt@cmco/api/semesterPattern/10000"
                        ],
                        "children" => [
                            [
                                "id" => 128419,
                                "weight" => 1,
                                "priority" => "LAST",
                                "semesterPattern" => [
                                    "id" => 10000,
                                    "uri" => "//ubt@cmco/api/semesterPattern/10000"
                                ],
                                "children" => [
                                ],
                                "type" => "LINK",
                                "valid" => true,
                                "organization" => [
                                    "id" => 14569,
                                    "nameDe" => "Rechtswissenschaften",
                                    "nameEn" => "Law",
                                    "uri" => "//ubt@cmorg/api/organizations/14569"
                                ],
                                "requirement" => false,
                                "courseEquivalenceUri" => "//ubt@cmco/api/courseEquivalences/87902",
                                "templateUri" => "//ubt@cmco/api/nodes/128385",
                                "uri" => "//ubt@cmco/api/nodes/128419"
                            ]
                        ],
                        "type" => "OFFER",
                        "valid" => true,
                        "organization" => [
                            "id" => 14569,
                            "nameDe" => "Rechtswissenschaften",
                            "nameEn" => "Law",
                            "uri" => "//ubt@cmorg/api/organizations/14569"
                        ],
                        "requirement" => false,
                        "templateUri" => "//ubt@cmco/api/nodes/128380",
                        "uri" => "//ubt@cmco/api/nodes/128418"
                    ]
                ],
                "type" => "MODULE",
                "valid" => true,
                "organization" => [
                    "id" => 14569,
                    "nameDe" => "Rechtswissenschaften",
                    "nameEn" => "Law",
                    "uri" => "//ubt@cmorg/api/organizations/14569"
                ],
                "requirement" => false,
                "lastOfferedIn" => [
                    "id" => 243,
                    "name" => "Wintersemester 2022/23",
                    "shortName" => "W 2022/23",
                    "type" => "WINTER",
                    "orderValue" => 151,
                    "begin" => "2022-10-01",
                    "end" => "2023-03-31",
                    "lectureBegin" => "2022-10-17",
                    "lectureEnd" => "2023-02-10",
                    "uri" => "//ubt@cmco/api/semesters/243"
                ],
                "templateUri" => "//ubt@cmco/api/nodes/128378",
                "uri" => "//ubt@cmco/api/nodes/128412"
            ]
        ],
        "type" => "FOCUS",
        "valid" => true,
        "requirement" => false,
        "lastOfferedIn" => [
            "id" => 243,
            "name" => "Wintersemester 2022/23",
            "shortName" => "W 2022/23",
            "type" => "WINTER",
            "orderValue" => 151,
            "begin" => "2022-10-01",
            "end" => "2023-03-31",
            "lectureBegin" => "2022-10-17",
            "lectureEnd" => "2023-02-10",
            "uri" => "//ubt@cmco/api/semesters/243"
        ],
        "uri" => "//ubt@cmco/api/nodes/128389"
    ];
}
