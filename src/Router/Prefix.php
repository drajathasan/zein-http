<?php
/**
 * @author Drajat Hasan
 * @email drajathasan20@gmail.com
 * @create date 2022-04-22 07:27:56
 * @modify date 2022-04-22 13:09:35
 * @license GPLv3
 * @desc [description]
 */

namespace Zein\Http\Router;

use Closure;
use Zein\Http\Router;

class Prefix
{
    private static $instance = null;
    private string $prefix;
    
    public static function getInstance()
    {
        if (is_null(self::$instance)) self::$instance = new Prefix;

        return self::$instance;
    }

    public function getPrefix(string $key = '')
    {
        if (!is_numeric($key) && empty($key)) return $this->prefix;
        return isset($this->prefix[$key]) ? $this->prefix[$key] : null;
    }

    public function getLastPrefix()
    {
        return $this->getPrefix(array_key_last($this->prefix));
    }

    public function group(Closure $callback)
    {
        Router::getInstance()->setPrefix(self::getInstance()->prefix);
        $callback();
        Router::getInstance()->setPrefix('/');
    }

    public function prefix(string $prefixPath)
    {
        self::getInstance()->prefix = $prefixPath;
        return self::getInstance();
    }
}