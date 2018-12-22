<?php
/**
 * Created by PhpStorm.
 * User: guokeling
 * Date: 2018/12/20
 * Time: 20:49
 */

namespace PHPHelper\helpers;

class ArrayHelper
{
    /**
     * 获取数组指定的key的值, key不存在则使用默认值.
     *
     * @param      $array
     * @param      $key
     * @param null $default
     * @return null
     */
    public static function getValue($array, $key, $default = null)
    {
        if ($key instanceof \Closure) {
            return $key($array, $default);
        }

        if (is_array($key)) {
            $lastKey = array_pop($key);
            foreach ($key as $keyPart) {
                $array = static::getValue($array, $keyPart);
            }
            $key = $lastKey;
        }

        if (is_array($array) && (isset($array[$key]) || array_key_exists($key, $array))) {
            return $array[$key];
        }

        if (($pos = strrpos($key, '.')) !== false) {
            $array = static::getValue($array, substr($key, 0, $pos), $default);
            $key = substr($key, $pos + 1);
        }

        if (is_object($array)) {
            return $array->$key;
        } elseif (is_array($array)) {
            return (isset($array[$key]) || array_key_exists($key, $array)) ? $array[$key] : $default;
        } else {
            return $default;
        }
    }

    /**
     * 将数组转换为json串，并转换中文编码.
     *
     * @param $array
     * @return mixed
     */
    public static function arrayToJsonFormat($array)
    {
        return StringHelper::unicodeDecode(json_encode($array));
    }

    /**
     * 将数组转换为字符串，忽略key，所有的value以[]包裹。
     *
     * @param array $params
     * @return string
     */
    public static function arrayToStringFormat($params = [])
    {
        $return = [];
        if (!empty($params)) {
            if (is_array($params)) {
                foreach ($params as $value) {
                    if (!empty($value)) {
                        $return[] = self::arrayToStringFormat($value);
                    }
                }
            } else {
                $return[] = '[' . $params . ']';
            }
        }
        return implode(' ', $return);
    }

    /**
     * 合并重叠区间.
     *
     * 如:[
     *          ['start' => 5, 'end' => 10],
     *          ['start' => 7, 'end' => 15],
     *          ['start' => 20, 'end' => 30]
     *    ]
     * 返回:[
     *          ['start' => 5, 'end' => 15],
     *          ['start' => 20, 'end' => 30]
     *    ]
     *
     * @param array $rangeList
     * @return array
     */
    public static function getNoOverlapRangeList($rangeList = [])
    {
        if (empty($rangeList)) {
            return [];
        }
        $noOverlapRangeList = [];
        foreach ($rangeList as $item) {
            if (empty($noOverlapRangeList)) {
                $noOverlapRangeList[] = $item;
            } else {
                $isOverlap = false;
                foreach ($noOverlapRangeList as &$noOverlapItem) {
                    if ($item['start'] <= $noOverlapItem['end'] && $item['end'] >= $noOverlapItem['start']) {
                        if ($item['start'] < $noOverlapItem['start']) {
                            $noOverlapItem['start'] = $item['start'];
                        }
                        if ($item['end'] > $noOverlapItem['end']) {
                            $noOverlapItem['end'] = $item['end'];
                        }
                        $isOverlap = true;
                    }
                }
                if (!$isOverlap) {
                    $noOverlapRangeList[] = $item;
                }
            }
        }
        return $noOverlapRangeList;
    }

    /**
     * 计算一个起止区间与一个不重叠的区间列表,对重合的差值进行累加,得出最终的end值.
     * 比如原本的工作区间是5号到18号,但是10号-20号不能工作,则截止时间应顺延至28号
     *
     * @param       $start
     * @param       $end
     * @param array $noOverlapRangeList
     * @return mixed
     */
    public static function getEndByRangeList($start, $end, $noOverlapRangeList = [])
    {
        if (empty($noOverlapRangeList)) {
            return $end;
        }
        foreach ($noOverlapRangeList as $item) {
            if ($item['start'] >= $end || $item['end'] <= $start) {
                continue;
            }
            if ($item['start'] >= $start) {
                $end = $end + $item['end'] - $item['start'];
            } else {
                $end = $item['end'] + $end - $start;
            }
        }

        return $end;
    }

}