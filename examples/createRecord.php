<?php
/**
 * Create a new record
 */
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../init.php';

$airtable = new \Airtable\Client($_ENV['AIRTABLE_API_KEY'], $_ENV['AIRTABLE_BASE_ID']);

$record = $airtable->companies->create([
    'name' => 'Basecamp',
    'website_url' => 'https://basecamp.com',
]);
var_dump($record);
