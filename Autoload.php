<?php
/**
 * Created by PhpStorm.
 * User: guokeling
 * Date: 2018/12/10
 * Time: 19:38
 */

/**
 * @param $className
 * @return bool
 */
function autoload($className)
{
    $classPath = str_replace('\\', DIRECTORY_SEPARATOR, $className);
    $classPath = __DIR__.'/../' . $classPath . '.php';
    if(is_file($classPath)) {
        require_once($classPath);
        if(class_exists($className, false)) {
            return true;
        }
    }
    return false;
}

spl_autoload_register('autoload');