<?php
/**
 * @author Drajat Hasan
 * @email drajathasan20@gmail.com
 * @create date 2022-04-13 12:45:34
 * @modify date 2022-04-23 07:12:59
 * @license GPLv3
 * @desc [description]
 */

namespace Zein\Http;

use DateTime;
use Zein\Http\Request;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Symfony\Component\HttpFoundation\JsonResponse;

class Response extends SymfonyResponse
{
    private static $instance = null;
    private array $cacheList = [
        'must_revalidate'  => false,
        'no_cache'         => false,
        'no_store'         => false,
        'no_transform'     => false,
        'public'           => true,
        'private'          => false,
        'proxy_revalidate' => false,
        'max_age'          => 600,
        's_maxage'         => 600,
        'immutable'        => true,
        'last_modified'    => '',
        'etag'             => '',
    ];
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

    public static function cache(array $inputedOptions = [])
    {
        $instance = static::getInstance();

        // set last modified and etag
        $instance->cacheList['last_modified'] = new DateTime;
        $instance->cacheList['etag'] = hash('sha256', date('this'));

        $instance->setCache(($instance->cacheList));

        return $instance;
    }

    public static function abort(int $httpStatusCode, $callback = '')
    {
        $instance = static::getInstance();
        $request = new Request;

        // Set http status code
        $instance->setStatusCode($httpStatusCode);

        if (is_callable($callback)) exit($callback($request, $instance));

        // set content
        $content = static::$statusTexts[$httpStatusCode]??'Error ' . $httpStatusCode;
        if ($request->isJson())
        {
            $content = json_encode(['status' => false, 'message' => $content]);
        }

        $instance->setContent($content);
        $instance->send();
        exit;
    }

    public static function json($data, int $httpResponseCode = 200)
    {
        $instance = static::getInstance();
        $instance->headers->set('Content-Type', 'application/json');
        $instance->setStatusCode($httpResponseCode);
        $instance->setContent(json_encode($data));
        $instance->send();
    }

    public static function __callStatic(string $method, array $paramter)
    {
        $static = new Static;

        if (isset($static->scope[$method]))
        {
            if ($method === 'any' && is_callable($paramter[0])) exit($paramter[0](static::getInstance()));

            static::getInstance()->headers->set('Content-Type', $static->scope[$method]);
            
            if (isset($paramter[1]) && is_callable($paramter[1])) exit(call_user_func_array($paramter[1], [static::getInstance(), $paramter[0]]));

            static::getInstance()->setContent($paramter[0])->send();
        }
    }

    public function __call(string $method, array $paramter)
    {
        if (isset($this->scope[$method]))
        {
            if ($method === 'any' && is_callable($paramter[0])) exit($paramter[0](static::getInstance()));

            static::getInstance()->headers->set('Content-Type', $this->scope[$method]);
            
            if (isset($paramter[1]) && is_callable($paramter[1])) exit(call_user_func_array($paramter[1], [static::getInstance(), $paramter[0]]));

            static::getInstance()->setContent($paramter[0])->send();
        }
    }
}