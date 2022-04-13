<?php

use Zein\Http\Router;

require __DIR__ . '/../vendor/autoload.php';

Router::get('/blog/{id}', 'ok');
Router::get('/bambang/{filename}', function(){
    echo 'Hai';
});
Router::post('/blog', 'No-Ok');

Router::run();