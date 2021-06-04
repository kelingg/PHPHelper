<?php
/**
 * Created by PhpStorm.
 * User: wind
 * Date: 2021/5/24
 * Time: 17:16
 */

namespace PHPHelper\libs;

class Number128
{
    private $lowValue;
    private $highValue;

    public function __construct($lowValue, $highValue)
    {
        $this->lowValue = $lowValue;
        $this->highValue = $highValue;
    }

    public function getLowValue()
    {
        return $this->lowValue;
    }

    public function getHighValue()
    {
        return $this->highValue;
    }

    public function setLowValue($lowValue)
    {
        $this->lowValue = $lowValue;
    }

    public function setHighValue($hiValue)
    {
        $this->highValue = $hiValue;
    }

    public function getLongArray()
    {
        return array($this->lowValue, $this->highValue);
    }
}

class OrderIter
{
    private static $IS_LITTLE_ENDIAN = True;
    private $size;
    private $index = 0;

    public function __construct($size)
    {
        $this->size = $size;
    }

    public function hasNext()
    {
        return $this->index < $this->size;
    }

    public function next()
    {
        if (self::$IS_LITTLE_ENDIAN) {
            $var10002 = $this->index;
            $var10000 = $var10002;
            $this->index = $var10002 + 1;
        } else {
            $var10000 = $this->size - 1 - $this->index++;
        }

        return $var10000;
    }
}

class CityHash
{
    private static $k0 = -4348849565147123417;
    private static $k1 = -5435081209227447693;
    private static $k2 = -7286425919675154353;
    private static $kMul = -7070675565921424023;
    private static $c1 = -862048943;
    private static $c2 = 461845907;

    /**
     * 拷贝指定数组起始和截止索引范围内的值生成新的数组, 包含$from, 不包含$to.
     *
     * @param $orgArray
     * @param $from
     * @param $to
     * @return array
     */
    public static function copyOfRange($orgArray, $from, $to)
    {
        if ($from < 0) {
            $from = 0;
        }
        if ($to > count($orgArray)) {
            $to = count($orgArray);
        }
        $result = array();
        for ($i = $from; $i < $to; $i++) {
            $result[] = $orgArray[$i];
        }

        return $result;
    }

    /**
     * 整数溢出处理.
     *
     * @param $value
     * @param int $num
     * @return int
     */
    public static function overflowLong($value, $num = 64)
    {
        $max_int = bcsub(bcpow(2, $num - 1), 1);
        $int_span = bcpow(2, $num);
        $min_int = bcsub(bcadd($max_int, 1), $int_span);
        $mod_value = bcmod($value, $int_span);
        if (bccomp($mod_value, $max_int) > 0) {
            $result = bcsub($mod_value, $int_span);
        } elseif (bccomp($mod_value, $min_int) < 0) {
            $result = bcadd($mod_value, $int_span);
        } else {
            $result = $mod_value;
        }
        return intval($result);
    }

    /**
     * 多整数相乘,
     *
     *
     * @return int
     */
    public static function smartMul()
    {
        $args = func_get_args();
        if (count($args) <= 0) {
            return 0;
        } elseif (count($args) == 1) {
            // 兼容递归调用时, 数组形式传参.
            if (is_array($args) && is_array($args[0]) && count($args[0]) >= 2) {
                $args = $args[0];
            } else {
                return 0;
            }
        }
        if (count($args) == 2) {
            return self::overflowLong(bcmul($args[0], $args[1]));
        } else {
            $last = array_pop($args);
            return self::overflowLong(bcmul(self::smartMul($args), $last));
        }
    }

    /**
     * 多整数相乘, 最多保留32位.
     *
     * @return int
     */
    public static function smartMul32()
    {
        $args = func_get_args();
        if (count($args) <= 0) {
            return 0;
        } elseif (count($args) == 1) {
            // 兼容递归调用时, 数组形式传参.
            if (is_array($args) && is_array($args[0]) && count($args[0]) >= 2) {
                $args = $args[0];
            } else {
                return 0;
            }
        }
        if (count($args) == 2) {
            return self::overflowLong(bcmul($args[0], $args[1]), 32);
        } else {
            $last = array_pop($args);
            return self::overflowLong(bcmul(self::smartMul32($args), $last), 32);
        }
    }

    /**
     * 多整数相加.
     *
     * @return int
     */
    public static function smartAdd()
    {
        $args = func_get_args();
        if (count($args) <= 0) {
            return 0;
        } elseif (count($args) == 1) {
            // 兼容递归调用时, 数组形式传参.
            if (is_array($args) && is_array($args[0]) && count($args[0]) >= 2) {
                $args = $args[0];
            } else {
                return 0;
            }
        }
        if (count($args) == 2) {
            return self::overflowLong(bcadd($args[0], $args[1]));
        } else {
            $last = array_pop($args);
            return self::overflowLong(bcadd(self::smartAdd($args), $last));
        }
    }

    /**
     * 多整数相加, 最多保留32位.
     *
     * @return int
     */
    public static function smartAdd32()
    {
        $args = func_get_args();
        if (count($args) <= 0) {
            return 0;
        } elseif (count($args) == 1) {
            // 兼容递归调用时, 数组形式传参.
            if (is_array($args) && is_array($args[0]) && count($args[0]) >= 2) {
                $args = $args[0];
            } else {
                return 0;
            }
        }
        if (count($args) == 2) {
            return self::overflowLong(bcadd($args[0], $args[1]), 32);
        } else {
            $last = array_pop($args);
            return self::overflowLong(bcadd(self::smartAdd32($args), $last), 32);
        }
    }

    /**
     * 无符号右移, 等同于java的>>>.
     *
     * @param $a
     * @param $b
     * @param int $maxSize
     * @return int|number
     */
    public static function uRShift($a, $b, $maxSize = null)
    {
        if (is_null($maxSize)) {
            $maxSize = 8 * PHP_INT_SIZE;
        }
        if ($b == 0) {
            return $a;
        } elseif ($a >= 0) {
            return $a >> $b;
        } else {
            $x = decbin($a);
            $len = strlen($x);
            if ($len > $maxSize) {
                $x = substr($x, ($len - $maxSize), $maxSize);
            } elseif ($len < $maxSize) {
                $x = str_repeat('0', ($maxSize - $len)) . $x;
            }
            $x = str_repeat('0', $b) . substr($x, 0, $maxSize - $b);
            return bindec($x);
        }
    }

    public static function uRShift32($a, $b)
    {
        return self::uRShift($a, $b, 32);
    }

    public static function reverseBytesInt($i)
    {
        return self::overflowLong($i << 24, 32) | self::overflowLong(($i & 65280) << 8, 32) | self::uRShift32($i, 8) & 65280 | self::uRShift32($i, 24);
    }

    public static function reverseBytesLong($i)
    {
        $i = ($i & 71777214294589695) << 8 | self::uRShift($i, 8) & 71777214294589695;
        return $i << 48 | ($i & 4294901760) << 16 | self::uRShift($i, 16) & 4294901760 | self::uRShift($i, 48);
    }

    public static function hash32($data)
    {
        $len = count($data);
        if ($len <= 24) {
            return $len <= 12 ? ($len <= 4 ? self::hash32Len0to4($data) : self::hash32Len5to12($data)) : self::hash32Len13to24($data);
        } else {
            $g = self::smartMul32(-862048943, $len);
            $f = $g;
            $data4 = self::smartMul32(self::fetch32($data, $len - 4), -862048943);
            $a0 = self::smartMul32(self::rotate32($data4, 17), 461845907);
            $data8 = self::smartMul32(self::fetch32($data, $len - 8), -862048943);
            $a1 = self::smartMul32(self::rotate32($data8, 17), 461845907);
            $data16 = self::smartMul32(self::fetch32($data, $len - 16), -862048943);
            $a2 = self::smartMul32(self::rotate32($data16, 17), 461845907);
            $data12 = self::smartMul32(self::fetch32($data, $len - 12), -862048943);
            $a3 = self::smartMul32(self::rotate32($data12, 17), 461845907);
            $data20 = self::smartMul32(self::fetch32($data, $len - 20), -862048943);
            $a4 = self::smartMul32(self::rotate32($data20, 17), 461845907);
            $h = $len ^ $a0;
            $h = self::rotate32($h, 19);
            $h = self::smartAdd32(self::smartMul32($h, 5), -430675100);
            $h ^= $a2;
            $h = self::rotate32($h, 19);
            $h = self::smartAdd32(self::smartMul32($h, 5), -430675100);
            $g ^= $a1;
            $g = self::rotate32($g, 19);
            $g = self::smartAdd32(self::smartMul32($g, 5), -430675100);
            $g ^= $a3;
            $g = self::rotate32($g, 19);
            $g = self::smartAdd32(self::smartMul32($g, 5), -430675100);
            $f += $a4;
            $f = self::rotate32($f, 19);
            $f = self::smartAdd32(self::smartMul32($f, 5), -430675100);
            $iters = floor(($len - 1) / 20);
            $pos = 0;

            do {
                $dp0 = self::smartMul32(self::fetch32($data, $pos), -862048943);
                $a0 = self::smartMul32(self::rotate32($dp0, 17), 461845907);
                $a1 = self::fetch32($data, $pos + 4);
                $dp8 = self::smartMul32(self::fetch32($data, $pos + 8), -862048943);
                $a2 = self::smartMul32(self::rotate32($dp8, 17), 461845907);
                $dp12 = self::smartMul32(self::fetch32($data, $pos + 12), -862048943);
                $a3 = self::smartMul32(self::rotate32($dp12, 17), 461845907);
                $a4 = self::fetch32($data, $pos + 16);
                $h ^= $a0;
                $h = self::rotate32($h, 18);
                $h = self::smartAdd32(self::smartMul32($h, 5), -430675100);
                $f = self::smartAdd32($f, $a1);
                $f = self::rotate32($f, 19);
                $f = self::smartMul32($f, -862048943);
                $g = self::smartAdd32($g, $a2);
                $g = self::rotate32($g, 18);
                $g = self::smartAdd32(self::smartMul32($g, 5), -430675100);
                $h ^= self::smartAdd32($a3, $a1);
                $h = self::rotate32($h, 19);
                $h = self::smartAdd32(self::smartMul32($h, 5), -430675100);
                $g ^= $a4;
                $g = self::smartMul32(self::reverseBytesInt($g), 5);
                $h = self::smartAdd32($h, self::smartMul32($a4, 5));
                $h = self::reverseBytesInt($h);
                $f = self::smartAdd32($f, $a0);
                $swapValue = $f;
                $f = $g;
                $g = $h;
                $h = $swapValue;
                $pos += 20;
                --$iters;
            } while ($iters > 0);

            $g = self::smartMul32(self::rotate32($g, 11), -862048943);
            $g = self::smartMul32(self::rotate32($g, 17), -862048943);
            $f = self::smartMul32(self::rotate32($f, 11), -862048943);
            $f = self::smartMul32(self::rotate32($f, 17), -862048943);
            $h = self::rotate32(self::smartAdd32($swapValue, $g), 19);
            $h = self::smartAdd32(self::smartMul32($h, 5), -430675100);
            $h = self::smartMul32(self::rotate32($h, 17), -862048943);
            $h = self::rotate32(self::smartAdd32($h, $f), 19);
            $h = self::smartAdd32(self::smartMul32($h, 5), -430675100);
            $h = self::smartMul32(self::rotate32($h, 17), -862048943);
            return $h;
        }
    }

    public static function hash64($data)
    {
        $len = count($data);
        if ($len <= 32) {
            if ($len <= 16) {
                return self::hashLen0to16($data);
            } else {
                return self::hashLen17to32($data);
            }
        } else if ($len <= 64) {
            return self::hashLen33to64($data);
        } else {
            $x = self::fetch64($data, $len - 40);
            $y = self::smartAdd(self::fetch64($data, $len - 16), self::fetch64($data, $len - 56));
            $z = self::hashLen16(self::fetch64($data, $len - 48) + $len, self::fetch64($data, $len - 24));
            $v = self::weakHashLen32WithSeeds($data, $len - 64, $len, $z);
            $y1 = self::smartAdd($y, -5435081209227447693);
            $w = self::weakHashLen32WithSeeds($data, $len - 32, $y1, $x);
            $x1 = self::smartMul($x, -5435081209227447693);
            $x = self::smartAdd($x1, self::fetch64($data, 0));
            $len = $len - 1 & -64;
            $pos = 0;
            do {
                $x = self::smartMul(self::rotate(self::smartAdd($x, $y, $v->getLowValue(), self::fetch64($data, $pos + 8)), 37), -5435081209227447693);
                $y = self::smartMul(self::rotate(self::smartAdd($y, $v->getHighValue(), self::fetch64($data, $pos + 48)), 42), -5435081209227447693);
                $x ^= $w->getHighValue();
                $y = self::smartAdd($y, $v->getLowValue(), self::fetch64($data, $pos + 40));
                $zw = self::smartAdd($z, $w->getLowValue());
                $z = self::smartMul(self::rotate($zw, 33), -5435081209227447693);
                $v1 = self::smartMul($v->getHighValue(), -5435081209227447693);
                $xw = self::smartAdd($x, $w->getLowValue());
                $v = self::weakHashLen32WithSeeds($data, $pos, $v1, $xw);
                $zw = self::smartAdd($z, $w->getHighValue());
                $ydp = self::smartAdd($y, self::fetch64($data, $pos + 16));
                $w = self::weakHashLen32WithSeeds($data, $pos + 32, $zw, $ydp);
                $swapValue = $x;
                $x = $z;
                $z = $swapValue;
                $pos += 64;
                $len -= 64;
            } while ($len != 0);
            $hvwl = self::hashLen16($v->getLowValue(), $w->getLowValue());
            $smy1 = self::smartMul(self::shiftMix($y), -5435081209227447693);
            $hvwh = self::hashLen16($v->getHighValue(), $w->getHighValue());

            return self::hashLen16(self::smartAdd($hvwl, $smy1, $swapValue), self::smartAdd($hvwh, $x));
        }
    }

    public static function hash64BySeed($data, $seed)
    {
        return self::hash64BySeed2($data, -7286425919675154353, $seed);
    }

    public static function hash64BySeed2($data, $seed0, $seed1)
    {
        return self::hashLen16(self::hash64($data) - $seed0, $seed1);
    }

    public static function hash128($data)
    {
        $len = count($data);
        return $len >= 16 ? self::hash128BySeedAndStart($data, 16, new Number128(self::fetch64($data, 0), self::smartAdd(self::fetch64($data, 8), -4348849565147123417))) : self::hash128BySeedAndStart($data, 0, new Number128(-4348849565147123417, -5435081209227447693));
    }

    public static function hash128BySeed($data, Number128 $seed)
    {
        return self::hash128BySeedAndStart($data, 0, $seed);
    }

    private static function hash128BySeedAndStart($byteArray, $start, Number128 $seed)
    {
        $len = count($byteArray) - $start;
        if ($len < 128) {
            return self::cityMurmur(self::copyOfRange($byteArray, $start, count($byteArray)), $seed);
        } else {
            $v = new Number128(0, 0);
            $w = new Number128(0, 0);
            $x = $seed->getLowValue();
            $y = $seed->getHighValue();
            $z = self::smartMul($len, -5435081209227447693);
            $v->setLowValue(self::smartAdd(self::smartMul(self::rotate($y ^ -5435081209227447693, 49), -5435081209227447693), self::fetch64($byteArray, $start)));
            $v->setHighValue(self::smartAdd(self::smartMul(self::rotate($v->getLowValue(), 42), -5435081209227447693), self::fetch64($byteArray, $start + 8)));
            $w->setLowValue(self::smartAdd(self::smartMul(self::rotate(self::smartAdd($y, $z), 35), -5435081209227447693), $x));
            $w->setHighValue(self::smartMul(self::rotate(self::smartAdd($x, self::fetch64($byteArray, $start + 88)), 53), -5435081209227447693));
            $pos = $start;
            do {
                $x = self::smartMul(self::rotate(self::smartAdd($x, $y, $v->getLowValue(), self::fetch64($byteArray, $pos + 8)), 37), -5435081209227447693);
                $y = self::smartMul(self::rotate(self::smartAdd($y, $v->getHighValue(), self::fetch64($byteArray, $pos + 48)), 42), -5435081209227447693);
                $x ^= $w->getHighValue();
                $y = self::smartAdd($y, $v->getLowValue(), self::fetch64($byteArray, $pos + 40));
                $z = self::smartMul(self::rotate(self::smartAdd($z, $w->getLowValue()), 33), -5435081209227447693);
                $v = self::weakHashLen32WithSeeds($byteArray, $pos, self::smartMul($v->getHighValue(), -5435081209227447693), self::smartAdd($x, $w->getLowValue()));
                $w = self::weakHashLen32WithSeeds($byteArray, $pos + 32, self::smartAdd($z, $w->getHighValue()), self::smartAdd($y, self::fetch64($byteArray, $pos + 16)));
                $swapValue = $x;
                $pos += 64;
                $x = self::smartMul(self::rotate(self::smartAdd($z, $y, $v->getLowValue(), self::fetch64($byteArray, $pos + 8)), 37), -5435081209227447693);
                $y = self::smartMul(self::rotate(self::smartAdd($y, $v->getHighValue(), self::fetch64($byteArray, $pos + 48)), 42), -5435081209227447693);
                $x ^= $w->getHighValue();
                $y = self::smartAdd($y, $v->getLowValue(), self::fetch64($byteArray, $pos + 40));
                $z = self::smartMul(self::rotate(self::smartAdd($swapValue, $w->getLowValue()), 33), -5435081209227447693);
                $v = self::weakHashLen32WithSeeds($byteArray, $pos, self::smartMul($v->getHighValue(), -5435081209227447693), self::smartAdd($x, $w->getLowValue()));
                $w = self::weakHashLen32WithSeeds($byteArray, $pos + 32, self::smartAdd($z, $w->getHighValue()), self::smartAdd($y, self::fetch64($byteArray, $pos + 16)));
                $swapValue = $x;
                $x = $z;
                $z = $swapValue;
                $pos += 64;
                $len -= 128;
            } while ($len >= 128);

            $x = self::smartAdd($x, self::smartMul(self::rotate(self::smartAdd($v->getLowValue(), $swapValue), 49), -4348849565147123417));
            $y = self::smartAdd(self::smartMul($y, -4348849565147123417), self::rotate($w->getHighValue(), 37));
            $z = self::smartAdd(self::smartMul($swapValue, -4348849565147123417), self::rotate($w->getLowValue(), 27));
            $w->setLowValue(self::smartMul($w->getLowValue(), 9));
            $v->setLowValue(self::smartMul($v->getLowValue(), -4348849565147123417));
            $tail_done = 0;
            while ($tail_done < $len) {
                $tail_done += 32;
                $y = self::smartAdd(self::smartMul(self::rotate(self::smartAdd($x, $y), 42), -4348849565147123417), $v->getHighValue());
                $w->setLowValue(self::smartAdd($w->getLowValue(), self::fetch64($byteArray, $pos + $len - $tail_done + 16)));
                $x = self::smartAdd(self::smartMul($x, -4348849565147123417), $w->getLowValue());
                $z = self::smartAdd($z, $w->getHighValue(), self::fetch64($byteArray, $pos + $len - $tail_done));
                $w->setHighValue(self::smartAdd($w->getHighValue(), $v->getLowValue()));
                $v = self::weakHashLen32WithSeeds($byteArray, $pos + $len - $tail_done, self::smartAdd($v->getLowValue(), $z), $v->getHighValue());
                $v->setLowValue(self::smartMul($v->getLowValue(), -4348849565147123417));
            }

            $x = self::hashLen16($x, $v->getLowValue());
            $y = self::hashLen16(self::smartAdd($y, $z), $w->getLowValue());
            return new Number128(self::smartAdd(self::hashLen16(self::smartAdd($x, $v->getHighValue()), $w->getHighValue()), $y), self::hashLen16(self::smartAdd($x, $w->getHighValue()), self::smartAdd($y, $v->getHighValue())));
        }
    }

    private static function hash32Len0to4($byteArray)
    {
        $b = 0;
        $c = 9;
        $len = count($byteArray);
        $var4 = $byteArray;
        $var5 = count($byteArray);

        for ($var6 = 0; $var6 < $var5; ++$var6) {
            $v = $var4[$var6];
            $b = self::smartAdd32(self::smartMul32($b, -862048943), $v);
            $c ^= $b;
        }

        return self::fmix(self::mur($b, self::mur($len, $c)));
    }

    private static function hash32Len5to12($byteArray)
    {
        $len = count($byteArray);
        $b = self::smartMul32($len, 5);
        $c = 9;
        $d = $b;
        $a = self::smartAdd32($len, self::fetch32($byteArray, 0));
        $b = self::smartAdd32($b, self::fetch32($byteArray, $len - 4));
        $c = self::smartAdd32($c, self::fetch32($byteArray, self::uRShift32($len, 1) & 4));
        return self::fmix(self::mur($c, self::mur($b, self::mur($a, $d))));
    }

    private static function hash32Len13to24($byteArray)
    {
        $len = count($byteArray);
        $a = self::fetch32($byteArray, (self::uRShift32($len, 1)) - 4);
        $b = self::fetch32($byteArray, 4);
        $c = self::fetch32($byteArray, $len - 8);
        $d = self::fetch32($byteArray, self::uRShift32($len, 1));
        $e = self::fetch32($byteArray, 0);
        $f = self::fetch32($byteArray, $len - 4);
        return self::fmix(self::mur($f, self::mur($e, self::mur($d, self::mur($c, self::mur($b, self::mur($a, $len)))))));
    }

    private static function hashLen0to16($byteArray)
    {
        $len = count($byteArray);
        if ($len >= 8) {
            $mul = -7286425919675154353 + ($len * 2);
            $a = self::fetch64($byteArray, 0) + -7286425919675154353;
            $b = self::fetch64($byteArray, $len - 8);
            $c = self::smartAdd(self::smartMul(self::rotate($b, 37), $mul), $a);
            $d = self::smartMul(self::smartAdd(self::rotate($a, 25), $b), $mul);
            return self::hashLen16ByMul($c, $d, $mul);
        } else if ($len >= 4) {
            $mul = -7286425919675154353 + ($len * 2);
            $a = self::fetch32($byteArray, 0) & 4294967295;
            return self::hashLen16ByMul($len + ($a << 3), self::fetch32($byteArray, $len - 4) & 4294967295, $mul);
        } else if ($len > 0) {
            $a = $byteArray[0] & 255;
            $b = $byteArray[self::uRShift($len, 1)] & 255;
            $c = $byteArray[$len - 1] & 255;
            $y = $a + ($b << 8);
            $z = $len + ($c << 2);
            $yl = self::smartMul($y, -7286425919675154353);
            $zl = self::smartMul($z, -4348849565147123417);
            $yzl = $yl ^ $zl;
            return self::smartMul(self::shiftMix($yzl), -7286425919675154353);
        } else {
            return -7286425919675154353;
        }
    }

    private static function hashLen17to32($byteArray)
    {
        $len = count($byteArray);
        $mul = -7286425919675154353 + ($len * 2);
        $a = self::smartMul(self::fetch64($byteArray, 0), -5435081209227447693);
        $b = self::fetch64($byteArray, 8);
        $c = self::smartMul(self::fetch64($byteArray, $len - 8), $mul);
        $d = self::smartMul(self::fetch64($byteArray, $len - 16), -7286425919675154353);
        $ab = self::smartAdd($a, $b);
        $abcd = self::smartAdd(self::rotate($ab, 43), self::rotate($c, 30), $d);
        $abc = self::smartAdd($a, self::rotate(self::smartAdd($b, -7286425919675154353), 18), $c);
        return self::hashLen16ByMul($abcd, $abc, $mul);
    }

    private static function hashLen33to64($byteArray)
    {
        $len = count($byteArray);
        $mul = -7286425919675154353 + ($len * 2);
        $a = self::smartMul(self::fetch64($byteArray, 0), -7286425919675154353);
        $b = self::fetch64($byteArray, 8);
        $c = self::fetch64($byteArray, $len - 24);
        $d = self::fetch64($byteArray, $len - 32);
        $e = self::smartMul(self::fetch64($byteArray, 16), -7286425919675154353);
        $f = self::smartMul(self::fetch64($byteArray, 24), 9);
        $g = self::fetch64($byteArray, $len - 8);
        $h = self::smartMul(self::fetch64($byteArray, $len - 16), $mul);
        $ag = self::smartAdd($a, $g);
        $b30c = self::smartAdd(self::rotate($b, 30), $c);
        $u = self::smartAdd(self::rotate($ag, 43), self::smartMul($b30c, 9));
        $v = self::smartAdd(self::smartAdd($a, $g) ^ $d, $f, 1);
        $uvm = self::smartMul(self::smartAdd($u, $v), $mul);
        $w = self::smartAdd(self::reverseBytesLong($uvm), $h);
        $x = self::smartAdd(self::rotate(self::smartAdd($e, $f), 42), $c);
        $vwm = self::smartMul(self::smartAdd($v, $w), $mul);
        $vwmg = self::smartAdd(self::reverseBytesLong($vwm), $g);
        $y = self::smartMul($vwmg, $mul);
        $z = self::smartAdd($e, $f, $c);
        $xzmy = self::smartAdd(self::smartMul(self::smartAdd($x, $z), $mul), $y);
        $a = self::smartAdd(self::reverseBytesLong($xzmy), $b);
        $zam = self::smartMul(self::smartAdd($z, $a), $mul);
        $b = self::smartMul(self::shiftMix(self::smartAdd($zam, $d, $h)), $mul);
        return self::smartAdd($b, $x);
    }

    private static function loadUnaligned64($byteArray, $start)
    {
        $result = 0;
        for ($orderIter = new OrderIter(8); $orderIter->hasNext(); $result |= $value) {
            $next = $orderIter->next();
            $value = ($byteArray[$next + $start] & 255) << $next * 8;
        }

        return $result;
    }

    private static function loadUnaligned32($byteArray, $start)
    {
        $result = 0;
        for ($orderIter = new OrderIter(4); $orderIter->hasNext(); $result |= $value) {
            $next = $orderIter->next();
            $value = ($byteArray[$next + $start] & 255) << $next * 8;
        }

        return $result;
    }

    private static function fetch64($byteArray, $start)
    {
        return self::loadUnaligned64($byteArray, $start);
    }

    private static function fetch32($byteArray, $start)
    {
        return self::loadUnaligned32($byteArray, $start);
    }

    private static function rotate($val, $shift)
    {
        return $shift == 0 ? $val : self::uRShift($val, $shift) | self::overflowLong($val << 64 - $shift, 64);
    }

    private static function rotate32($val, $shift)
    {
        return $shift == 0 ? $val : self::uRShift32($val, $shift) | self::overflowLong($val << 32 - $shift, 32);
    }

    private static function hashLen16ByMul($u, $v, $mul)
    {
        $a = self::smartMul(($u ^ $v), $mul);
        $a ^= self::uRShift($a, 47);
        $b = self::smartMul(($v ^ $a), $mul);
        $b ^= self::uRShift($b, 47);
        $b = self::smartMul($b, $mul);
        return $b;
    }

    private static function hashLen16($u, $v)
    {
        return self::hash128to64(new Number128($u, $v));
    }

    private static function hash128to64(Number128 $number128)
    {
        $lhv = $number128->getLowValue() ^ $number128->getHighValue();
        $a = self::smartMul($lhv, -7070675565921424023);
        $a ^= self::uRShift($a, 47);
        $b = self::smartMul(($number128->getHighValue() ^ $a), -7070675565921424023);
        $b ^= self::uRShift($b, 47);
        $b = self::smartMul($b, -7070675565921424023);
        return $b;
    }

    private static function shiftMix($val)
    {
        return $val ^ self::uRShift($val, 47);
    }

    private static function fmix($h)
    {
        $h ^= self::uRShift32($h, 16);
        $h = self::smartMul32($h, -2048144789);
        $h ^= self::uRShift32($h, 13);
        $h = self::smartMul32($h, -1028477387);
        $h ^= self::uRShift32($h, 16);
        return $h;
    }

    private static function mur($a, $h)
    {
        $a = self::smartMul32($a, -862048943);
        $a = self::rotate32($a, 17);
        $a = self::smartMul32($a, 461845907);
        $h ^= $a;
        $h = self::rotate32($h, 19);
        return self::smartAdd32(self::smartMul32($h, 5), -430675100);
    }

    private static function weakHashLen32WithSeedsByXYZ($w, $x, $y, $z, $a, $b)
    {
        $a = self::smartAdd($a, $w);
        $baz = self::smartAdd($b, $a, $z);
        $b = self::rotate($baz, 21);
        $c = $a;
        $a = self::smartAdd($a, $x);
        $a = self::smartAdd($a, $y);
        $b = self::smartAdd($b, self::rotate($a, 44));
        return new Number128(self::smartAdd($a, $z), self::smartAdd($b, $c));
    }

    private static function weakHashLen32WithSeeds($byteArray, $start, $a, $b)
    {
        return self::weakHashLen32WithSeedsByXYZ(self::fetch64($byteArray, $start), self::fetch64($byteArray, $start + 8), self::fetch64($byteArray, $start + 16), self::fetch64($byteArray, $start + 24), $a, $b);
    }

    private static function cityMurmur($byteArray, Number128 $seed)
    {
        $len = count($byteArray);
        $a = $seed->getLowValue();
        $b = $seed->getHighValue();
        $l = $len - 16;
        if ($l <= 0) {
            $a = self::smartMul(self::shiftMix(self::smartMul($a, -5435081209227447693)), -5435081209227447693);
            $c = self::smartAdd(self::smartMul($b, -5435081209227447693), self::hashLen0to16($byteArray));
            $d = self::shiftMix(self::smartAdd($a, ($len >= 8 ? self::fetch64($byteArray, 0) : $c)));
        } else {
            $c = self::hashLen16(self::smartAdd(self::fetch64($byteArray, $len - 8), -5435081209227447693), $a);
            $d = self::hashLen16(self::smartAdd($b, $len), self::smartAdd($c, self::fetch64($byteArray, $len - 16)));
            $a = self::smartAdd($a, $d);
            $pos = 0;

            do {
                $a ^= self::smartMul(self::shiftMix(self::smartMul(self::fetch64($byteArray, $pos), -5435081209227447693)), -5435081209227447693);
                $a = self::smartMul($a, -5435081209227447693);
                $b ^= $a;
                $c ^= self::smartMul(self::shiftMix(self::smartMul(self::fetch64($byteArray, $pos + 8), -5435081209227447693)), -5435081209227447693);
                $c = self::smartMul($c, -5435081209227447693);
                $d ^= $c;
                $pos += 16;
                $l -= 16;
            } while ($l > 0);
        }

        $a = self::hashLen16($a, $c);
        $b = self::hashLen16($d, $b);
        return new Number128($a ^ $b, self::hashLen16($b, $a));
    }
}

