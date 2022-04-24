<?php
/**
 * @author drajathasan20@gmail.com
 * @email drajathasan20@gmail.com
 * @create date 2022-04-25 06:36:07
 * @modify date 2022-04-25 06:42:22
 * @license GPLv3
 * @desc [description]
 */

namespace Zein\Http;

use GuzzleHttp\Client as HttpClient;

class Client
{
    private static function getClient(array $constructorOptions = [])
    {
        return new HttpClient($constructorOptions);
    }

    public static function get($url)
    {
        return static::getClient()->request('GET', $url);
    }
}