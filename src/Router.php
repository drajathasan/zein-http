<?php
/**
 * @author Drajat Hasan
 * @email drajathasan20@gmail.com
 * @create date 2022-04-12 13:55:52
 * @modify date 2022-04-14 08:53:21
 * @license GPLv3
 * @desc [description]
 */

namespace Zein\Http;

use Zein\Http\Request;
use Zein\Http\Router\Resolver;

class Router
{
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
        'method' => [
            'get',
            'post',
            'patch',
            'put',
            'delete'
        ],
    ];
    
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
        $this->getRequest();

        $requestMethod = strtolower($this->request->getMethod());

        if (isset($this->scope['method'][$requestMethod]))
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

        if (in_array($method, $instance->scope['method']))
        {
            $instance->route[$method][] = $parameter;
        }
    }
}
