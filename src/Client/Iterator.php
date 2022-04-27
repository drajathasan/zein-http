<?php
/**
 * @author Drajat Hasan
 * @email drajathasan20@gmail.com
 * @create date 2022-04-25 11:39:52
 * @modify date 2022-04-25 11:47:47
 * @license GPLv3
 * @desc [description]
 */

namespace Zein\Http\Client;

use Traversable;

interface Iterator extends Traversable {
    /* Methods */
    public function current();
    public function key();
    public function next();
    public function rewind();
    public function valid();
}