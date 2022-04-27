<?php
/**
 * @author drajathasan20@gmail.com
 * @email drajathasan20@gmail.com
 * @create date 2022-04-25 06:36:07
 * @modify date 2022-04-26 08:11:40
 * @license GPLv3
 * @desc [description]
 */

namespace Zein\Http;

use Exception;
use Iterator;
use GuzzleHttp\Client as HttpClient;
use Zein\Http\Client\{Supports,File};

class Client implements Iterator
{
    use Supports,File;
    
    private static $instance = null;
    private array $httpMethod = [
        'get','post','put','head',
        'delete','options','patch',
        'put'
    ];
    private $result;

    public static function getInstance()
    {
        if (is_null(self::$instance)) self::$instance = new Client;

        return self::$instance;
    }

    private static function getClient(array $constructorOptions = [])
    {
        return new HttpClient($constructorOptions);
    }

    private function call(string $method, string $url, array $options = [])
    {
        try {
            static::getInstance()->result = static::getClient()->request($method, $url, $options)->getBody();
        } catch (Exception $e) {
            die($e->getMessage());
        }

        return static::getInstance();
    }

    public function toArray()
    {
        $result = json_decode(static::getInstance()->result, true);

        return json_last_error_msg() ? (array)$result : $result;
    }

    public static function __callStatic($method, $parameter)
    {
        if (in_array($method, static::getInstance()->httpMethod))
        {
            return call_user_func_array([static::getInstance(), 'call'], array_merge([strtoupper($method)], $parameter));
        }
    }

    public function __toString()
    {
        if (static::getInstance()->downloaded) return '';
        
        $data = static::getInstance()->result->getContents();
        return is_string($data) ? $data : json_encode($data);
    }
}