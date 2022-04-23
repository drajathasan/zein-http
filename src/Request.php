<?php
/**
 * @author Drajat Hasan
 * @email drajathasan20@gmail.com
 * @create date 2022-04-08 11:25:46
 * @modify date 2022-04-21 14:00:24
 * @license GPLv3
 * @desc [description]
 */

namespace Zein\Http;

use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

class Request extends SymfonyRequest
{
    /**
     * Instance property
     *
     * @instance Zein/Request
     */
    private static $Instance;

    /**
     * Register scope
     *
     * @var array
     */
    private array $scope = [
        'input' => 'request',
        'query' => 'query',
        'server' => 'server',
        'files' => 'files'
    ];

    /**
     * Static function to get an Instance
     *
     * @return Zein\Request
     */
    private static function getInstance()
    {
        if (is_null(static::$Instance)) static::$Instance = parent::createFromGlobals();

        return static::$Instance;
    }

    /**
     * Get Instance from object context
     *
     * @return Zein\Request
     */
    public function getParent()
    {
        return static::getInstance();
    }

    /**
     * Get route path
     *
     * @param string $mode
     * @return string
     */
    public function getPath(string $mode = 'query')
    {
        if (!in_array($mode, ['query','server'])) throw new \Exception("{$mode} not available!");
            
        return static::getInstance()->{$mode}(($mode === 'query' ? 'route' : 'PATH_INFO'));
    }

    /**
     * Get input from json
     *
     * @param string $key
     * @return array
     */
    public function json(string $key = '')
    {
        $instance = static::getInstance();
        $data = json_decode($instance->getContent(), true);

        if (!empty($key))
        {
            return isset($data[$key]) ? $data[$key] : null;
        }

        return $data;
    }

    /**
     * Check if client request is json or not
     *
     * @return boolean
     */
    public function isJson()
    {
        return $this->server('HTTP_ACCEPT') === 'application/json' ? true : false;
    }

    /**
     * Call function from object context as static
     *
     * @param string $method
     * @param array $parameter
     * @return void
     */
    public static function __callStatic(string $method, array $parameter)
    {
        $static = new Static;

        if (array_key_exists($method, $static->scope))
        {
            $class = static::getInstance()->{$static->scope[$method]};

            if (count($parameter) === 0) return $class;

            return call_user_func_array([$class, 'get'], $parameter);
        }

        if (method_exists($static, $method))
        {
            return call_user_func_array([$static, $method], $parameter);
        }
    }

    /**
     * Undocumented function
     *
     * @param string $method
     * @param array $parameter
     * @return void
     */
    public function __call(string $method, array $parameter)
    {
        if (array_key_exists($method, $this->scope))
        {
            $class = static::getInstance()->{$this->scope[$method]};

            if (count($parameter) === 0) return $class;

            return call_user_func_array([$class, 'get'], $parameter);
        }


        if (method_exists($this, $method))
        {
            return call_user_func_array([$this, $method], $parameter);
        }
    }
}