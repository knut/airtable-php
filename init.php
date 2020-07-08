<?php

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

require __DIR__ . '/src/Airtable/Record.php';
require __DIR__ . '/src/Airtable/Response.php';
require __DIR__ . '/src/Airtable/Request.php';
require __DIR__ . '/src/Airtable/Client.php';
