<?php
/**
 * Created by PhpStorm.
 * User: guokeling
 * Date: 2018/12/11
 * Time: 12:03
 */

require_once __DIR__ . '/../Autoload.php';

try {
    $email = 'kelingg@163.com';
    $mobile = 18857872345;
    if(\PHPHelper\helpers\ValidateHelper::isEmail($email)) {
        echo $email . " is a valid email!\n";
    } else {
        echo $email . " is a invalid email!\n";
    }
    if(\PHPHelper\helpers\ValidateHelper::isMobile($mobile)) {
        echo $mobile . " is a valid mobile!\n";
    } else {
        echo $mobile . " is a invalid mobile!\n";
    }
} catch (Exception $e) {
    echo $e->getMessage();
}
