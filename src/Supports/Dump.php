<?php
/**
 * @author Drajat Hasan
 * @email drajathasan20@gmail.com
 * @create date 2022-04-21 07:11:01
 * @modify date 2022-04-21 07:12:04
 * @license GPLv3
 * @desc [description]
 */

if (!function_exists('dd'))
{
    function dd($char, bool $exit = true)
    {
        echo '<pre>';
        var_dump($char);
        echo '</pre>';
        if ($exit) exit;
    }
}