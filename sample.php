<?php

#include_once (dirname(__FILE__).'/src/SearchEngine.php');
require __DIR__ . '/vendor/autoload.php';

$client = new SearchEngine();
$client->setEngine('google.com');
//$result = $client->search(['hypertext processor','payment free']); // ['hypertext processor','payment free']
//$client->resultCount = 10;
//$result = $client->search(['hypertext processor','payment free']);
$result = $client->search(['free payment']);

print_r($result);

