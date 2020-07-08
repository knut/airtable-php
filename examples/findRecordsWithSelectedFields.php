<?php
/**
 * Find records and limit result with only selected fields
 */
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../init.php';

$airtable = new \Airtable\Client($_ENV['AIRTABLE_API_KEY'], $_ENV['AIRTABLE_BASE_ID']);

$records = $airtable->companies->find([
    'fields' => ['name', 'website_url']
]);
var_dump($records);
