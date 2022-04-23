<?php
/**
 * @author Drajat Hasan
 * @email drajathasan20@gmail.com
 * @create date 2022-04-21 14:14:52
 * @modify date 2022-04-21 14:47:12
 * @license GPLv3
 * @desc [description]
 */

namespace Zein\Http\Router;

use Closure;
use ReflectionMethod;
use ReflectionFunction;

trait Parameter
{
    public function scanMethodParameter(string $className, string $classMethod, array $parameter)
    {
        $reflection = new ReflectionMethod($className, $classMethod);
        $reflectionParams = $reflection->getParameters();
        
        $paramterAsClass = [];
        foreach ($reflectionParams as $key => $reflectionParam) {
            $class = $reflectionParam->getClass();
            if (!is_null($class)) 
            {
                $paramterAsClass[] = new $class->name;
            }
        }

        return array_merge($paramterAsClass, $parameter);
    }

    public function scanClosureParameter(Closure $functionName, array $parameter)
    {
        $reflection = new ReflectionFunction($functionName);
        $reflectionParams = $reflection->getParameters();
        
        $paramterAsClass = [];
        foreach ($reflectionParams as $key => $reflectionParam) {
            $class = $reflectionParam->getClass();
            if (!is_null($class)) 
            {
                $paramterAsClass[] = new $class->name;
            }
        }

        return array_merge($paramterAsClass, $parameter);
    }
}