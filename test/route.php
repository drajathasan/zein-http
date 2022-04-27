<?php

@error_reporting(-1);
@ini_set('display_errors', true);

use Zein\Http\{Router,Request,Response};

require __DIR__ . '/../vendor/autoload.php';

Router::getInstance()->setMode('server');

Router::get('/download/khs', function(){
    Response::download(__DIR__ . '/transkrip-nilai-marisa-maratul-m..pdf');
    exit;
});

Router::controller(\App\ExampleController::class)->group(function(){
    Router::prefix('api')->group(function(){
        Router::middleware('before')->group(function(){
            Router::get('/member', 'run');
        
            Router::post('/dosen/{id}', function(Request $request){
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