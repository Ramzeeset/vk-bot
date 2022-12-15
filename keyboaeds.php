<?php

const HELLO_KEYBOARD = ["one_time" => false,
    "buttons" => [
        [
            [
                "action" => [
                    "type" => "text",
                    "label" => "Создать комнату",
                    "payload" => ""
                ],
                "color" => "primary"
            ]
        ],
        [
            [
                "action" => [
                    "type" => "text",
                    "label" => "Подключиться к комнате",
                    "payload" => ""
                ],
                "color" => "primary"
            ]
        ]
    ]
];

const MAIN_KEYBOARD = ["one_time" => false,
    "buttons" => [[
        ["action" =>
            ["type" => "text",
                "label" => "Список нужного",
                "payload" => ""],
            "color" => "primary"
        ]
        ],
        [
            [
                "action" => [
                    "type" => "text",
                    "label" => "Добавить",
                    "payload" => ""
                ],
                "color" => "primary"
            ]
        ],
        [
            [
                "action" => [
                    "type" => "text",
                    "label" => "Убрать",
                    "payload" => ""
                ],
                "color" => "primary"
            ]
        ]
    ]
];

const SELECT_KEYBOARD = ["one_time" => false,
    "buttons" => [
        [
            [
                "action" => [
                    "type" => "text",
                    "label" => "Назад",
                    "payload" => ""
                ],
                "color" => "primary"
            ]
        ]
    ]
];