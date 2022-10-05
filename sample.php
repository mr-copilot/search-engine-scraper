<?php

include_once (dirname(__FILE__).'/src/SearchEngine.php');

$client = new SearchEngine();
$client->setEngine('google.com');
$result = $client->search(['hello']);
print_r($result);

