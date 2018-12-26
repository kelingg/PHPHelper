<?php
/**
 * Created by PhpStorm.
 * User: guokeling
 * Date: 2018/12/26
 * Time: 12:01
 */
require_once __DIR__ . '/../Autoload.php';

$configs = array(
    'default' => array(
        'isEnable' => true,
        'mod' => 1,
        'ext' => 'txt',
        'wholeLogCategories' => array(
            'update_user_name'
        ),
        'wholeLogTargets' => array(
            123,
            124
        ),
    ),
    'userInfo' => array(
        'isEnable' => true,
        'dir' => '/home/logs/biz_log/user_info',
        'mod' => 5,
        'wholeLogCategories' => array(
            'update_user_name'
        ),
        'wholeLogTargets' => array(
            123,
            124
        ),
    )
);

// 在入口文件处统一初始化配置即可.
\PHPHelper\libs\BizLog::initConfigs($configs);

$requestData = array('id' => 123, 'username' => 'abc');
echo "track_log: \n";
$result = \PHPHelper\libs\BizLog::trackLog('请求改名', 123, 'update_user_name', $requestData);
var_dump($result);

echo "error_log: \n";
$result = \PHPHelper\libs\BizLog::errorLog('请求改名处理失败', 123, 'update_user_name', $requestData, array('message' => '数据不合法!'), 100001);
var_dump($result);

echo "data_change_log: \n";
$changeData = array('id' => 123, 'origin_username' => 'aaa', 'username' => 'abc');
$result = \PHPHelper\libs\BizLog::dataChangeLog('请求改名处理完成', 123, 'update_user_name', $changeData, $requestData, array('appCode' => 'userInfo'));
var_dump($result);