<?php
/**
 * @author Drajat Hasan
 * @email drajathasan20@gmail.com
 * @create date 2022-04-22 07:27:56
 * @modify date 2022-04-22 14:38:00
 * @license GPLv3
 * @desc [description]
 */

namespace Zein\Http\Router;

use Closure;
use Zein\Http\Router;

class Middleware
{
    private static $instance = null;
    private string $middlewareClass;
    
    public static function getInstance()
    {
        if (is_null(self::$instance)) self::$instance = new Middleware;

        return self::$instance;
    }

    public function group(Closure $callback)
    {
        Router::getInstance()->setMiddleware(self::getInstance()->middlewareClass);
        $callback();
        Router::getInstance()->setMiddleware('');
    }

    public function middleware(string $middlewareClass)
    {
        self::getInstance()->middlewareClass = $middlewareClass;
        return self::getInstance();
    }
}