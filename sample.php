<?php

#include_once (dirname(__FILE__).'/src/SearchEngine.php');
require __DIR__ . '/vendor/autoload.php';

$client = new SearchEngine();
$client->setEngine('google.com');
$result = $client->search(['hypertext processor','payment free']); // ['python']
print_r($result);

