<?php
/**
 * Created by PhpStorm.
 * User: guokeling
 * Date: 2018/12/22
 * Time: 15:28
 */

$email = 'abc_def@163.com.cn';

if(filter_var($email, FILTER_VALIDATE_EMAIL)){
    echo $email . ' is a valid email';
} else {
    echo $email . ' is a invalid email';
}
echo "\n";
$list = array(
    'a@test.com',
    'a@test.com.',
    'a@test&.com',
);

$result = filter_var_array($list, FILTER_VALIDATE_EMAIL);
var_dump($result);