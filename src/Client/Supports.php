<?php
/**
 * @author Drajat Hasan
 * @email drajathasan20@gmail.com
 * @create date 2022-04-25 11:44:17
 * @modify date 2022-04-26 07:18:14
 * @license GPLv3
 * @desc [description]
 */

namespace Zein\Http\Client;

trait Supports
{
    private $position = 0;
    
    public function rewind() {
        $this->position = 0;
    }

    public function current() {
        return $this->arrayResult[$this->position];
    }

    public function key() {
        return $this->arrayResult;
    }

    public function next() {
        ++$this->arrayResult;
    }

    public function valid() {
        return isset($this->arrayResult[$this->position]);
    }
}