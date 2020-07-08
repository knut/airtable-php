<?php
/**
 * Find records by pre-defined view in Airtable
 */
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../init.php';

$airtable = new \Airtable\Client($_ENV['AIRTABLE_API_KEY'], $_ENV['AIRTABLE_BASE_ID']);

$records = $airtable->companies->find([
    'view' => 'Name of your view in Airtable'
]);
var_dump($records);
