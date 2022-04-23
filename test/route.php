<?php

@error_reporting(-1);
@ini_set('display_errors', true);

use Zein\Http\{Router,Request};

require __DIR__ . '/../vendor/autoload.php';

Router::get('/statistic/gmd', function(){
    echo 'Ini GMD';
});

Router::get('/statistic/gmb', function(){
    echo 'Ini GMB';
});

Router::controller(\App\ExampleController::class)->group(function(){
    Router::prefix('api')->group(function(){
        Router::middleware('before')->group(function(){
            Router::get('/member', 'run');
        
            Router::post('/dosen/{id}/{string}', function(Request $request, $id){
                dd(func_get_args());
            });
        });
    });

    Router::prefix('apo')->group(function(){
        Router::get('/member', 'test');
        Router::post('/member', 'test');
    });
});


Router::get('/', function(){
    echo 'Hai';
});

Router::run();