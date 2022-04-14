<?php

@error_reporting(-1);
@ini_set('display_errors', true);

use Zein\Http\Router;

require __DIR__ . '/../vendor/autoload.php';

Router::get('/blog/{id}', 'ok');
Router::get('/bambang/{filename}', function(){
    echo 'Hai';
});
Router::post('/blog', 'No-Ok');

Router::run();