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
    [
        'url' => 'http://sega.bg/',
        'name' => 'Сега'
    ],
    [
        'url' => 'http://www.mediapool.bg/',
        'name' => 'Mediapool'
    ],
    [
        'url' => 'https://dariknews.bg/',
        'name' => 'Darik News'
    ],
    [
        'url' => 'https://technews.bg/',
        'name' => 'Tech News'
    ],
];

$blacklist = [
    'апр',
    'април',
    'блиц',
    'българия',
    'видео',
    'виж',
    'вход',
    'във',
    'две',
    'ден',
    'деня',
    'дневник',
    'днес',
    'ето',
    'или',
    'има',
    'как',
    'към',
    'май',
    'минути',
    'най',
    'новини',
    'обновена',
    'още',
    'пик',
    'повече',
    'през',
    'при',
    'развлечение',
    'само',
    'свят',
    'сега',
    'след',
    'снимка',
    'снимки',
    'спорт',
    'със',
    'фотогалерия',
    'час',
    'часа',
];

const MAX_WORDS = 20;

function fetch($url) {
    return file_get_contents($url);
}

function getWords($content) {
    $content = strip_tags($content);
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
