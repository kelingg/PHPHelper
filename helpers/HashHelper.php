<?php
/**
 * Created by PhpStorm.
 * User: guokeling
 * Date: 2018/12/24
 * Time: 10:20
 */

namespace PHPHelper\helpers;

/**
 * Class HashHelper, hash算法类.
 *
 * @package PHPHelper\helpers
 */
class HashHelper
{
    /**
     * 按照time33算法计算hash值.
     *
     * hash(i) = hash(i-1) * 33 + str[i]
     *
     * @param string $str
     * @return int
     */
    public static function getHashByTime33($str = '')
    {
        $hash = 0;
        $strMd5 = md5($str);
        $seed = 5;
        $len = 32;
        for ($i = 0; $i < $len; $i++) {
            $hash = ($hash << $seed) + $hash + ord($strMd5[$i]);
        }

        return $hash & 0x7FFFFFFF;
    }

}