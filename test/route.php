<?php

@error_reporting(-1);
@ini_set('display_errors', true);

use Zein\Http\Router;

require __DIR__ . '/../vendor/autoload.php';

Router::get('/', function(){
    echo 'Harno';
});

Router::get('/bambang/{filename}', function(){
    echo 'Hai';
});
Router::post('/blog', function(){
    echo 'Blog post';
});

Router::run();