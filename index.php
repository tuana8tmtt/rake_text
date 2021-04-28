<?php

require_once __DIR__ . '/vendor/autoload.php';

use tuana8tmt\TextExtract\Index;

$greeting = new Index();
$return = $greeting->extract("Keyword extraction is not that difficult after all. There are many libraries that can help you with keyword extraction. Rapid automatic keyword extraction is one of those.", __DIR__ ."/stop_words.txt");
print_r($return);
