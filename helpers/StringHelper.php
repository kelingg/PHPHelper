<?php
/**
 * Created by PhpStorm.
 * User: guokeling
 * Date: 2018/12/10
 * Time: 19:14
 */

namespace PHPHelper\helpers;

/**
 * Class StringHelper,字符串处理帮助类.
 *
 * @package PHPHelper\helpers
 */
class StringHelper
{

    /**
     * 处理录入(如TextArea)数据返回一维数组.
     *
     * @param       $inputData
     * @param array $colSplitFlags
     * @return array
     */
    public static function getInputDataSingle($inputData, $colSplitFlags = array(",", "\t", " "))
    {
        if (!empty($colSplitFlags) && !is_array($colSplitFlags)) {
            $colSplitFlags = array($colSplitFlags);
        }
        $resultArray = array();
        $inputDataList = self::getDataListByFlags($inputData, array("\n"));
        foreach ($inputDataList as $row) {
            $resultArray = array_merge($resultArray, self::getDataListByFlags(trim($row), $colSplitFlags));
        }
        //去空
        $resultArray = array_filter($resultArray,
            function ($item) {
                echo $item . "\n";
                $itemNew = trim($item);
                return !empty($itemNew);
            }
        );
        $resultArray = array_unique($resultArray);   //去重
        $resultArray = array_values($resultArray);   //重组
        return $resultArray;
    }

    /**
     * 处理录入(如TextArea)数据返回二维数组.
     *
     * @param       $inputData
     * @param array $rowSplitFlags
     * @param array $colSplitFlags
     * @return array
     */
    public static function getInputDataMutil($inputData, $rowSplitFlags = array("\n", ";"), $colSplitFlags = array("\t", ",", " "))
    {
        if (!empty($rowSplitFlags) && !is_array($rowSplitFlags)) {
            $rowSplitFlags = array($rowSplitFlags);
        }
        if (!empty($colSplitFlags) && !is_array($colSplitFlags)) {
            $colSplitFlags = array($colSplitFlags);
        }
        $resultArray = array();
        $inputDataList = self::getDataListByFlags($inputData, $rowSplitFlags);
        foreach ($inputDataList as $row) {
            $resultArray[] = self::getDataListByFlags(trim($row), $colSplitFlags);
        }

        return $resultArray;
    }

    /**
     * 根据分割标记字符串返回分割后的数组.
     *
     * @param string $data
     * @param array  $flags
     * @return array|string
     */
    public static function getDataListByFlags($data = '', $flags = array())
    {
        if (empty($data)) {
            return '';
        }
        if (is_array($data)) {
            return $data;
        } elseif (!is_string($data)) {
            return '';
        }
        if (empty($flags) || !is_array($flags)) {
            return $data;
        }
        // 遍历分割字符列表,以第一个在目标字符串中出现的分割字符进行分割.
        foreach ($flags as $flag) {
            if (strpos($data, $flag) !== false) {
                return explode($flag, $data);
            }
        }

        return $data;
    }

    /**
     * 转换编码，将Unicode编码转换成可读的utf-8编码.
     *
     * @param string $value 转码字符.
     *
     * @return mixed
     */
    public static function unicodeDecode($value)
    {
        $pattern = '/\\\u([\w]{4})/i';
        preg_match_all($pattern, $value, $matches);
        if (!empty($matches)) {
            for ($j = 0; $j < count($matches[0]); $j++) {
                $from = $matches[0][$j];
                if (strpos($from, '\\u') === 0) {
                    $code = base_convert(substr($from, 2, 2), 16, 10);
                    $code2 = base_convert(substr($from, 4), 16, 10);
                    $c = chr($code) . chr($code2);
                    $c = mb_convert_encoding($c, 'UTF-8', 'UCS-2');
                    $to = $c;
                    $value = str_replace($from, $to, $value);
                }
            }
        }
        return $value;
    }

    /**
     * 将数据打印并返回内容.
     *
     * @param $data
     * @return string
     */
    public static function convertToString($data)
    {
        ob_start();
        print_r($data);
        $str = ob_get_contents();
        ob_end_clean();
        return $str;
    }

    /**
     * 将json串转换为array,并返回格式化数据方便web展示.
     *
     * @param $param
     * @return string
     */
    public static function showJsonFormat($param)
    {
        $array = json_decode($param, true);
        if (empty($array)) {
            $array = $param;
        } elseif (!is_array($array)) {
            //防止双重json_encode
            $array = json_decode($array, true);
        }
        $string = self::convertToString($array);
        return '<pre>' . $string . '</pre>';
    }

}