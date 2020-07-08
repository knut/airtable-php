<?php
/**
 * Find record by ID
 */
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../init.php';

$airtable = new \Airtable\Client($_ENV['AIRTABLE_API_KEY'], $_ENV['AIRTABLE_BASE_ID']);

$record = $airtable->companies->findById('rec3APJV3yRHlpHoA');
var_dump($record->toJson());

