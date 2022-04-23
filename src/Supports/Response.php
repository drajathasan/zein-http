<?php
/**
 * @author Drajat Hasan
 * @email drajathasan20@gmail.com
 * @create date 2022-04-23 07:04:15
 * @modify date 2022-04-23 07:21:40
 * @license GPLv3
 * @desc [description]
 */

if (!function_exists('response'))
{
    function response(string $content = '')
    {
        $response = new \Zein\Http\Response;
        $response->setStatusCode(200);
        
        if (!empty($content)) $response->setContent($content);

        return $response;
    }
}