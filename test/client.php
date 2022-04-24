<?php
@error_reporting(-1);
@ini_set('display_errors', true);

require __DIR__ . '/../vendor/autoload.php';

use Zein\Http\Client;

$Quotes = Client::get('https://sia.ump.ac.id/quotes');

dd($Quotes->getBody()->getContents());