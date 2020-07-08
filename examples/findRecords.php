<?php
/**
 * List all records
 */
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../init.php';

$airtable = new \Airtable\Client($_ENV['AIRTABLE_API_KEY'], $_ENV['AIRTABLE_BASE_ID']);

// Find all
$records = $airtable->companies->find();
var_dump($records);
