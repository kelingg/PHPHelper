<?php
/**
 * Created by PhpStorm.
 * User: guokeling
 * Date: 2018/12/10
 * Time: 19:10
 */

require_once __DIR__ . '/../Autoload.php';

$data = "11223,11223,12333
1233,23333,123312
32323,1123,12123,
231234,12132,3123";

try {
    $result = \PHPHelper\helpers\StringHelper::getInputDataSingle($data);
    print_r($result);
    $result = \PHPHelper\helpers\StringHelper::getInputDataMutil($data);
    print_r($result);
} catch (Exception $e) {
    echo $e->getMessage();
}
