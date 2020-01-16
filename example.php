<?php

require 'TelegramEntityParser.php';

$entities = [
        0 => [
                'offset' => 0,
                'length' => 10,
                'type' => 'bold',
        ],
        1 => [
                'offset' => 11,
                'length' => 12,
                'type' => 'italic',
        ],
        2 => [
                'offset' => 24,
                'length' => 9,
                'type' => 'underline',
        ],
        3 => [
                'offset' => 34,
                'length' => 13,
                'type' => 'strikethrough',
        ],
        4 => [
                'offset' => 48,
                'length' => 69,
                'type' => 'bold',
        ],
        5 => [
                'offset' => 53,
                'length' => 59,
                'type' => 'italic',
        ],
        6 => [
                'offset' => 65,
                'length' => 25,
                'type' => 'strikethrough',
        ],
        7 => [
                'offset' => 91,
                'length' => 21,
                'type' => 'underline',
        ],
        8 => [
                'offset' => 118,
                'length' => 10,
                'type' => 'text_link',
                'url' => 'http://www.example.com/',
        ],
        9 => [
                'offset' => 154,
                'length' => 23,
                'type' => 'code',
        ],
        10 => [
                'offset' => 178,
                'length' => 37,
                'type' => 'pre',
        ],
        11 => [
                'offset' => 216,
                'length' => 79,
                'type' => 'pre',
        ],
];

$text = 'bold *text
italic *text
underline
strikethrough
bold italic bold italic bold strikethrough underline italic bold bold
inline URL
inline mention of a user
inline fixed-width code
pre-formatted fixed-width code block

pre-formatted fixed-width code block written in the Python programming language';

echo entitiesToHtml($text, $entities);