<?php
/**
 * @author Drajat Hasan
 * @email drajathasan20@gmail.com
 * @create date 2022-04-12 13:54:19
 * @modify date 2022-04-18 16:47:00
 * @license GPLv3
 * @desc [description]
 */

namespace Zein\Http\Router;

use Zein\Http\{Router,Request,Response};

class Resolver
{
    private static $Instance = null;
    private $router;
    private $route;

    private function __construct(Router $router)
    {
        $this->router = $router;
    }

    private static function getInstance(Router $router)
    {
        if (is_null(self::$Instance)) self::$Instance = new Resolver($router);

        return self::$Instance;
    }

    /**
     * Get match path
     *
     * @return void
     */
    public function parsePath()
    {
        $request = $this->router->getRequest();

        $this->route = array_values(array_filter($this->router->getRoute($request->server('REQUEST_METHOD')), function($route) use($request) {
            $registerPath = explode('/', trim($route[0], '/'));
            $requestPath = explode('/', trim($request->getPath(), '/'));

            if (count($registerPath) === count($requestPath) && $registerPath[0] === $requestPath[0]) return true;
        }))[0]??'';

        return $this;
    }

    /**
     * Make a call to controller
     *
     * @return void
     */
    public function call()
    {
        if (empty($this->route)) exit(Response::abort(404));

        $registerPath = explode('/', trim($this->route[0], '/'));
        $currentPath = explode('/', trim($this->router->getRequest()->getPath(), '/'));

        $parameter = [];
        foreach ($registerPath as $index => $path) {
            if (preg_match('/{|}/i', $path)) $parameter[] = $currentPath[$index];
        }

        if (is_array($this->route[1]) && class_exists($this->route[1][0]))
        {
            call_user_func_array([(new $this->route[1][0]), $this->route[1][1]], $parameter);
        }
        else
        {
            call_user_func_array($this->route[1], $parameter);
        }
    }

    public static function match(Router $router)
    {
        Resolver::getInstance($router)->parsePath()->call();
    }
}