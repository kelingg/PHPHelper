<?php
/**
 * Created by PhpStorm.
 * User: guokeling
 * Date: 17/6/30
 * Time: 15:19
 */

namespace PHPHelper\helpers;

/**
 * Class ValidateHelper,验证帮助类.
 *
 * @package PHPHelper\helpers
 */
class ValidateHelper
{

    /**
     * 验证是否为合法手机号
     *
     * @param $str
     * @return int
     */
    public static function isMobile($str)
    {
        $regExp = '/^1\d{10}$/';
        return preg_match($regExp, $str);
    }

    /**
     * 验证是否为合法邮箱
     *
     * @param $str
     * @param $useFilter
     * @return int
     */
    public static function isEmail($str, $useFilter = true)
    {
        if ($useFilter) {
            return filter_var($str, FILTER_VALIDATE_EMAIL);
        } else {
            $RegExp = '/^[a-z\d]+([-_.][a-z\d]+)*@([a-z\d]+[-.])+[a-z\d]{2,4}$/i';
            return preg_match($RegExp, $str);
        }
    }

    /**
     * 验证是否是合法IP地址.
     *
     * @param $str
     * @return mixed
     */
    public static function isIp($str)
    {
        return filter_var($str, FILTER_VALIDATE_IP);
    }

    /**
     * 验证是否是合法URL地址.
     *
     * @param $str
     * @return mixed
     */
    public static function isUrl($str)
    {
        return filter_var($str, FILTER_VALIDATE_URL);
    }

}