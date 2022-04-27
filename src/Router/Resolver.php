<?php
/**
 * @author Drajat Hasan
 * @email drajathasan20@gmail.com
 * @create date 2022-04-12 13:54:19
 * @modify date 2022-04-25 07:38:15
 * @license GPLv3
 * @desc [description]
 */

namespace Zein\Http\Router;

use Closure;
use Exception;
use Zein\Http\{Router,Request,Response};

class Resolver
{
    use Parameter;
    
    private static $Instance = null;
    private $paramter;
    private $router;
    private $route;
    private $result;

    private function __construct(Router $router)
    {
        $this->router = $router;
    }

    public static function getInstance(Router $router)
    {
        if (is_null(self::$Instance)) self::$Instance = new Resolver($router);

        return self::$Instance;
    }

    /**
     * Get match path
     *
     * @return void
     */
    private function parsePath()
    {
        $request = $this->router->getRequest();
        $routes = $this->router->getRoute($request->server('REQUEST_METHOD'));

        // Regular search
        $matchRoute = array_values(array_filter($routes??[], function($route) use($request) {            
            $registerPath = explode('/', trim($route[0], '/'));
            $requestPath = explode('/', trim($request->getPath($this->router->getMode()), '/'));

            if ($this->comparePerPath($registerPath, $requestPath)) return true;
        }))[0]??'';

        // Set route
        $this->route = $matchRoute;

        return $this;
    }

    /**
     * Comparing registered path with requested path
     * by how many path requested and match per path
     *
     * @param array $registerPath
     * @param array $requestedPath
     * @return void
     */
    private function comparePerPath(array $registerPath, array $requestedPath)
    {
        $pass = 0;
        if (count($registerPath) === count($requestedPath))
        {
            foreach ($registerPath as $index => $path) {
                if ($path === $requestedPath[$index]) $pass++;
                if (preg_match('/{\w+}/i', $path)) {
                    $pass++;
                }
            }
        }

        return count($registerPath) === $pass;
    }

    /**
     * filter path to preparing before call
     *
     * @return void
     */
    private function filter()
    {
        if (empty($this->route)) exit(Response::abort(404));

        $registerPath = explode('/', trim($this->route[0], '/'));
        $currentPath = explode('/', trim($this->router->getRequest()->getPath(), '/'));

        $this->parameter = [];
        foreach ($registerPath as $index => $path) {
            if (preg_match('/{|}/i', $path) && isset($currentPath[$index])) $this->parameter[] = $currentPath[$index];
        }

        // Middleware first before main controller run
        if (isset($this->route[2]) && !is_null($this->route[2]))
        {
            if (!class_exists($this->route[2]))
            {
                Response::abort(500, function($request, $response){
                    $response->setContent("Middleware {$this->route[2]} not found!");
                    $response->send();
                });
            }

            $middleware = new $this->route[2];
            $middleware->handle(new Request, function() {
                exit($this->call());
            });
        }

        return $this;
    }

    private function call()
    {
        try {
            if (is_array($this->route[1]))
            {
                if (!class_exists($this->route[1][0])) throw new Exception("Class {$this->route[1][0]} not found!");
                
                // Scan parameter if has class or just string,int etc
                $parameter = $this->scanMethodParameter($this->route[1][0], $this->route[1][1], $this->parameter);
                $this->result = call_user_func_array([(new $this->route[1][0]), $this->route[1][1]], $parameter);
            }
            else if ($this->route[1] instanceof Closure)
            {
                $this->result = call_user_func_array($this->route[1], $this->scanClosureParameter($this->route[1], $this->parameter));
            }
            else
            {
                $type = gettype($this->route[1]);
                throw new Exception("Parameter #1 of \$this->route must be an controller class or closure. {$type} given.");
            }
        } catch (Exception $e) {
            Response::abort(500, function($request, $response) use($e) {
                $response->setContent($e->getMessage());
                $response->send();
            });
        }

        return $this;
    }

    private function getResult()
    {
        if ($this->result instanceof Response)
        {
            $this->result->send();
            exit;
        }

        switch (gettype($this->result)) {
            case 'array':
            case 'object':
                Response::json($this->result);
                break;
            default:
                Response::html($this->result);
                break;
        }
        exit;
    }

    public static function match(Router $router)
    {
        Resolver::getInstance($router)->parsePath()->filter()->call()->getResult();
    }
}