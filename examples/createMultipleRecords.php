<?php
/**
 * Create a new record
 */
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../init.php';

$airtable = new \Airtable\Client($_ENV['AIRTABLE_API_KEY'], $_ENV['AIRTABLE_BASE_ID']);

$companies = [
    [
        'name' => 'Apple',
        'website_url' => 'https://apple.com',
    ],
    [
        'name' => 'Google',
        'website_url' => 'https://google.com',
    ]
];

$records = $airtable->companies->create($companies);
var_dump($records);
