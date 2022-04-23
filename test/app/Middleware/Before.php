<?php
/**
 * @author Drajat Hasan
 * @email drajathasan20@gmail.com
 * @create date 2022-04-22 13:37:29
 * @modify date 2022-04-22 19:04:20
 * @license GPLv3
 * @desc [description]
 */

namespace App\Middleware;

use Closure;
use Zein\Http\{Response,Request};

class Before
{
    public function handle(Request $request, Closure $next)
    {
        if (is_null($request->json('myname'))) exit(Response::json(['status' => false, 'message' => 'Failed!'], 401));
        
        $next();
    }
}