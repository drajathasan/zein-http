<?php
/**
 * @author Drajat Hasan
 * @email drajathasan20@gmail.com
 * @create date 2022-04-13 12:45:34
 * @modify date 2022-04-14 10:31:07
 * @license GPLv3
 * @desc [description]
 */

namespace Zein\Http;

use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Symfony\Component\HttpFoundation\JsonResponse;

class Response extends SymfonyResponse
{
    private static $instance = null;
    private array $scope = [
        'text' => 'text/plain',
        'html' => 'text/html',
        'any' => 'closure'
    ];

    private static function getInstance()
    {
        if (is_null(self::$instance)) self::$instance = new Response;

        return self::$instance;
    }

    public static function json($data, int $httpResponseCode = 200)
    {
        $response = new JsonResponse;
        $response->setStatusCode($httpResponseCode);
        $response->setData($data);
        $response->send();
    }

    public static function __callStatic(string $method, array $paramter)
    {
        $static = new Static;

        if (isset($static->scope[$method]))
        {
            if ($method === 'any' && is_callable($paramter[0])) exit($paramter[0](static::getInstance()));

            static::getInstance()->headers->set('Content-Type', $static->scope[$method]);
            
            if (is_callable($paramter[1])) exit(call_user_func_array($paramter[1], [static::getInstance(), $paramter[0]]));

            static::getInstance()->setContent($paramter[0])->send();
        }
    }
}