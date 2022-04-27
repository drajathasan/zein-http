<?php
@error_reporting(-1);
@ini_set('display_errors', true);

require __DIR__ . '/../vendor/autoload.php';

use Zein\Http\Client;

$download = Client::download('http://192.168.7.88:7000/test/route.php/download/khs', __DIR__);

if ($download->isDownloaded())
{
    $download->saveAs(rand(1,10000) . '-khs.pdf');
}