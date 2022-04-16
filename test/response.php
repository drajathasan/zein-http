<?php

use Zein\Http\Response;

@error_reporting(-1);
@ini_set('display_errors', true);

require __DIR__ . '/../vendor/autoload.php';

$object = new stdClass;
$object->name = 'drajat';
Response::cache()->json($object);