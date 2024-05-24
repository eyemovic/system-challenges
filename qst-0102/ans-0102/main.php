<?php

$filePath = $argv[1];

$html = file_get_contents($filePath);

preg_match_all('/(?:href|src|[\'"\(])\s*=\s*[\'"](https?:\/\/[^"\']*)["\']/i', $html, $matches);
$links = $matches[1];

$urlList = [];
foreach ($links as $link) {
    $scheme = parse_url($link, PHP_URL_SCHEME);
    $host = parse_url($link, PHP_URL_HOST);
    $origin = $scheme . '://' . $host;

    $urlList[$origin][] = $link;
}
ksort($urlList);

foreach ($urlList as $origin => $urls) {
    echo 'origin=' . $origin . ' total=' . count($urls) . "\n";

    sort($urls);
    foreach ($urls as $url) {
        echo $url . "\n";
    }
    echo "\n";
}
