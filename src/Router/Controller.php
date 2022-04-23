<?php
/**
 * @author Drajat Hasan
 * @email drajathasan20@gmail.com
 * @create date 2022-04-22 07:27:56
 * @modify date 2022-04-23 06:22:29
 * @license GPLv3
 * @desc [description]
 */

namespace Zein\Http\Router;

use Closure;
use Zein\Http\Router;

class Controller
{
    private static $instance = null;
    private string $controller;
    
    public static function getInstance()
    {
        if (is_null(self::$instance)) self::$instance = new Controller;

        return self::$instance;
    }

    public function group(Closure $callback)
    {
        Router::getInstance()->setController(self::getInstance()->controller);
        $callback();
        Router::getInstance()->setController('');
    }

    public function controller(string $controllerClass)
    {
        self::getInstance()->controller = $controllerClass;
        return self::getInstance();
    }
}