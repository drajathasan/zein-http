<?php
/**
 * @author Drajat Hasan
 * @email drajathasan20@gmail.com
 * @create date 2022-04-22 02:07:57
 * @modify date 2022-04-22 02:09:25
 * @license GPLv3
 * @desc [description]
 */

if (!function_exists('pathExtract'))
{
    function pathExtract(string $path, string $delimiter = '/', $callback = '')
    {
        return explode($delimiter, trim($path, '/'));
    }
}