<?php
$filePath = $argv[1];

$html = file_get_contents($filePath);

preg_match_all('/(?:href|src|[\'"\(])\s*=\s*[\'"](https?:\/\/[^"\']*)["\']/i', $html, $matches);
$urls = $matches[1];

foreach ($urls as $url) {
    echo $url;
    echo "\n";
}
