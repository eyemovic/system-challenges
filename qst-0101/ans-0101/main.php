<?php
$file_content = file_get_contents($argv[1]);

// Regex pattern
$pattern = "/(?:http|https):\/\/[^'\"\s]+/";
preg_match_all($pattern, $file_content, $matches);

$extracted_urls = array_unique($matches[0]);
sort($extracted_urls);

foreach ($extracted_urls as $url) {
    echo rtrim($url, '\'"') . PHP_EOL;
}
?>
<!--php -f main.php ../eyemovic.txt-->
