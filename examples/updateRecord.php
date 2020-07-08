<?php
/**
 * Update record
 */
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../init.php';

$airtable = new \Airtable\Client($_ENV['AIRTABLE_API_KEY'], $_ENV['AIRTABLE_BASE_ID']);

$record = $airtable->companies->create([
    'name' => 'New Company',
    'website_url' => 'https://example.com',
]);

$record->name = 'An Updated Company';
var_dump($record);
$updatedRecord = $airtable->companies->update($record);
var_dump($updatedRecord);
