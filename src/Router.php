<?php
/**
 * @author Drajat Hasan
 * @email drajathasan20@gmail.com
 * @create date 2022-04-12 13:55:52
 * @modify date 2022-04-25 07:37:53
 * @license GPLv3
 * @desc [description]
 */

namespace Zein\Http;

use Zein\Http\Request;
use Zein\Http\Router\{Resolver,Prefix,Middleware,Controller};

class Router
{   
    /**
     * Route prefix
     *
     * @var string
     */
    private string $prefix = '';

    /**
     * Route middleware
     *
     * @var string
     */
    private string $middlewareClass = '';

    /**
     * Route controller
     *
     * @var string
     */
    private string $controllerClass = '';

    /**
     * Route directory path
     *
     * @var string
     */
    private string $routeDirectoryPath = __DIR__ . '/../test/';

    /**
     * Current route
     *
     * @var array
     */
    private array $route = [];

    /**
     * Allow method scope
     *
     * @var array
     */
    private array $scope = [
        'httpmethod' => [
            'get',
            'post',
            'patch',
            'put',
            'delete'
        ],
        'class' => [
            'middleware' => Middleware::class,
            'prefix' => Prefix::class,
            'controller' => Controller::class
        ]
    ];

    /**
     * Path Mode
     * 
     * options: query => ?route= | server - get value from $_SERVER['PATH_INFO'] => index.php/route/path
     */
    private string $mode = 'query';
    
    /**
     * Request instance
     *
     * @var null
     */
    private static $request = null;

    /**
     * Router instance
     *
     * @var [type]
     */
    private static $instance = null;
    
    /**
     * Get request instance
     *
     * @return void
     */
    private static function getInstance()
    {
        if (is_null(self::$instance)) self::$instance = new Router;
        
        return self::$instance;
    }

    /**
     * Get Request instance
     *
     * @return void
     */
    public function getRequest()
    {
        if (is_null(self::$request)) self::$request = new Request;

        return self::$request;
    }

    /**
     * All route listed in route property
     *
     * @param string $method
     * @return void
     */
    public function getRoute(string $method = '')
    {
        if (empty($method)) return Router::$instance->route;

        return Router::$instance->route[strtolower($method)]??null;
    }

    /**
     * Getter for router mode
     *
     * @return void
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * Setter for router mode
     *
     * @param string $mode
     * @return void
     */
    public function setMode(string $mode)
    {
        $this->mode = $mode;
    }

    /**
     * Set route prefix
     *
     * @return void
     */
    public function setPrefix(string $prefix)
    {
        $this->prefix = $prefix;
    }

    /**
     * Set route middleware
     *
     * @return void
     */
    public function setMiddleware(string $middlewareClass)
    {
        $this->middlewareClass = $middlewareClass;
    }

    /**
     * Set controller class
     *
     * @param string $controllerClass
     * @return void
     */
    public function setController(string $controllerClass)
    {
        $this->controllerClass = $controllerClass;
    }

    public function getMiddleware()
    {
        $middlewarePath = self::getInstance()->routeDirectoryPath . 'middleware.php';

        if (file_exists($middlewarePath))
        {
            return $middlewarePath;   
        }
    }

    /**
     * Set route base path
     *
     * @return void
     */
    public static function basePath(string $routeDirectoryPath)
    {
        self::getInstance()->routeDirectoryPath = $routeDirectoryPath;
    }

    /**
     * Running match route
     *
     * @return void
     */
    public static function run()
    {
        Resolver::match(Router::getInstance());
    }

    public function __call(string $method, array $parameter)
    {
        // $this->getRequest();

        $requestMethod = strtolower($this->getRequest()->getMethod());

        if (isset($this->scope['httpmethod'][$requestMethod]))
        {
            $this->route[$requestMethod][] = $parameter[0];
            return;
        }
    }

    public static function __callStatic(string $method, array $parameter)
    {
        $instance = self::getInstance();
        $instance->getRequest();

        $requestMethod = static::$request->getMethod();

        if (method_exists($instance, $method))
        {
            return call_user_func_array([$instance, $method], $parameter);
        }

        // Call http method
        if (in_array($method, $instance->scope['httpmethod']))
        {
            if (!empty($instance->prefix)) {
                $parameter[0] = $instance->prefix . $parameter[0];
            }

            if (!empty($instance->controllerClass) && is_string($parameter[1]))
            {
                $parameter[1] = [$instance->controllerClass, $parameter[1]];
            }

            if (!empty($instance->middlewareClass) && !is_null($middleware = $instance->getMiddleware()))
            {
                $middlewareList = require $middleware;
                $parameter[] = $middlewareList['middleware'][$instance->middlewareClass]??null;
            }

            $instance->route[$method][] = $parameter;
        }

        // Call module name
        if (array_key_exists($method, $instance->scope['class']))
        {
            $class = $instance->scope['class'][$method];
            return call_user_func_array([$class::getInstance(), $method], $parameter);
        }
    }
}
