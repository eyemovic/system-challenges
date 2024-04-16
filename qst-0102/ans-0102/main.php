<?php

function extractAndGroupURLs($filePath) {
    $content = file_get_contents($filePath);

    // Regex pattern
    $pattern = "/(?:http|https):\/\/[^'\"\s]+/";

    preg_match_all($pattern, $content, $matches);

    $urls = $matches[0];
    $groupedURLs = [];

    foreach ($urls as $url) {
        $parsedUrl = parse_url($url);
        $origin = $parsedUrl['scheme'] . '://' . $parsedUrl['host'];

        // Check origin exists
        if (!array_key_exists($origin, $groupedURLs)) {
            $groupedURLs[$origin] = [];
        }

        // Check url exists
        if (!in_array($url, $groupedURLs[$origin])) {
            $groupedURLs[$origin][] = $url;
        }
    }

    ksort($groupedURLs);

    foreach ($groupedURLs as $origin => $urls) {
        $total = count($urls);
        echo "origin=$origin total=$total\n";
        foreach ($urls as $url) {
            echo "$url\n";
        }
        echo "\n";
    }
}

$filePath = $argv[1];
extractAndGroupURLs($filePath);
?>

<!--php -f main.php ../eyemovic.txt-->
