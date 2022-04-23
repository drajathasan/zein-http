<?php
/**
 * @author Drajat Hasan
 * @email drajathasan20@gmail.com
 * @create date 2022-04-21 07:03:34
 * @modify date 2022-04-22 11:00:56
 * @license GPLv3
 * @desc [description]
 */

namespace Zein\Http\Router;

use Closure;

trait Group
{    
    private $groupBy = '';
    
    public function group(Closure $callback)
    {
        $this->{$this->groupBy} = true;
        $callback();
        $this->{$this->groupBy} = false;
        $this->groupBy = '';
        return self::getInstance();
    }
}