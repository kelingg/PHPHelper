<?php
/**
 * Created by PhpStorm.
 * User: guokeling
 * Date: 2018/12/21
 * Time: 15:00
 */
require_once __DIR__ . '/../Autoload.php';

$list = array();
echo '$list的key(id)的值为:';
echo \PHPHelper\helpers\ArrayHelper::getValue($list, 'id', 0);
echo "\n";

$list2 = array('id' => '123', 'name' => '王多鱼');
echo '$list2 to json:';
echo \PHPHelper\helpers\ArrayHelper::arrayToJsonFormat($list2);
echo "\n";

$list3 = array(
    '这是第一条信息',
    '这是第二条信息'
);
echo '$list3 to string:';
echo \PHPHelper\helpers\ArrayHelper::arrayToStringFormat($list3);
echo "\n";

$list4 = array(
    array('start' => 10, 'end' => 15),
    array('start' => 12, 'end' => 20),
    array('start' => 20, 'end' => 30),
);

echo '$list4 range merge result:';
$list5 = \PHPHelper\helpers\ArrayHelper::getNoOverlapRangeList($list4);
print_r($list5);
echo "\n";

echo '5-18 end result1:';
print_r(\PHPHelper\helpers\ArrayHelper::getEndByRangeList(5, 18, $list5));
echo "\n";
echo '5-8 end result2:';
print_r(\PHPHelper\helpers\ArrayHelper::getEndByRangeList(5, 8, $list5));
echo "\n";

print_r(spl_classes());

