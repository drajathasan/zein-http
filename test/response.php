<?php

use Zein\Http\Response;

require __DIR__ . '/../vendor/autoload.php';

$object = new stdClass;
$object->name = 'drajat';
Response::json($object);