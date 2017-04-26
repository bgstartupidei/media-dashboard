<?php

$sites = [
    [
        'url' => 'http://www.dnevnik.bg/',
        'name' => 'Дневник'
    ],
    [
        'url' => 'http://pik.bg/',
        'name' => 'ПИК'
    ],
    [
        'url' => 'http://www.blitz.bg/',
        'name' => 'БЛИЦ'
    ],
    [
        'url' => 'https://www.24chasa.bg/',
        'name' => '24 часа'
    ],
    [
        'url' => 'https://trud.bg/',
        'name' => 'Труд'
    ],
];

$blacklist = [
    'апр',
    'видео',
    'след',
    'свят',
    'дневник',
    'деня',
    'спорт',
    'развлечение',
    'новини',
    'виж',
    'снимка',
    'повече',
    'пик',
    'само',
    'снимки',
    'или',
    'през',
    'блиц',
    'часа',
    'фотогалерия',
];

const MAX_WORDS = 20;

function fetch($url) {
    return file_get_contents($url);
}

function getWords($content) {
    $content = preg_replace('/[^а-яА-Я ]|[^\S\r\n]+/u', ' ', $content);
    $content = mb_strtolower($content);
    $counted = array_count_values(explode(' ', $content));
    $counted = array_filter($counted, function($word) {
        global $blacklist;
        return mb_strlen($word) > 2 && !in_array($word, $blacklist);
    }, ARRAY_FILTER_USE_KEY);
    arsort($counted);
    return array_slice($counted, 0, MAX_WORDS);
}

$result = [];

foreach ($sites as $site) {
    $content = fetch($site['url']);
    $wordlist = getWords($content);
    $site['words'] = $wordlist;
    $site['updated'] = time();
    $result[] = $site;
}

echo json_encode($result, JSON_UNESCAPED_UNICODE);
