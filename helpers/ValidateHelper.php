<?php
/**
 * Created by PhpStorm.
 * User: guokeling
 * Date: 17/6/30
 * Time: 15:19
 */

namespace PHPHelper\helpers;

class ValidateHelper
{

    /**
     * 验证是否为手机号
     *
     * @param $str
     * @return int
     */
    public static function isMobile($str) {
        $regExp = '/^1\d{10}$/';
        return preg_match($regExp, $str);
    }

    /**
     * 验证是否为邮箱
     *
     * @param $str
     * @return int
     */
    public static function isEmail($str) {
        $RegExp = '/^[a-z\d]+([-_.][a-z\d]+)*@([a-z\d]+[-.])+[a-z\d]{2,4}$/i';
        return preg_match($RegExp, $str);
    }

}