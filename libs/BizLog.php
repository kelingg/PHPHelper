<?php
/**
 * Created by PhpStorm.
 * User: guokeling
 * Date: 2018/12/25
 * Time: 20:16
 */

namespace PHPHelper\libs;

use PHPHelper\helpers\ArrayHelper;
use PHPHelper\helpers\FileHelper;
use PHPHelper\helpers\ServerHelper;

/**
 * Class BizLog, 业务日志类
 *
 * @package PHPHelper\libs
 */
class BizLog
{
    const APP_CODE_DEFAULT = 'default';

    const SUFFIX_TYPE_MONTH = 'month';
    const SUFFIX_TYPE_DAY = 'day';

    const LOG_TYPE_TRACK = 'track';
    const LOG_TYPE_ERROR = 'error';
    const LOG_TYPE_DATA_CHANGE = 'change';

    // 日志配置,支持多个项目不同配置.
    private static $configs = array();

    // 日志实例列表.
    private static $instances = array();

    // 项目编码.
    private $appCode = self::APP_CODE_DEFAULT;
    // 是否开启日志.
    private $isEnable = false;
    // 日志目录.
    private $dir = '/home/logs/biz_log/' . self::APP_CODE_DEFAULT;
    // 日志文件前缀.
    private $prefix = 'biz_log';
    // 日志文件后缀,默认以天结尾,如20181212.
    private $suffix = self::SUFFIX_TYPE_DAY;
    // 日志文件分片数量.
    private $mod = 20;
    // 日志文件扩展名.
    private $ext = 'log';
    // 需要记录所有日志的category列表(主要应用于track_log,便于排查问题),如果包含'ALL',则都会记录.
    private $wholeLogCategories = array();
    // 需要记录所有日志的target列表(主要应用于track_log,便于排查问题).
    private $wholeLogTargets = array();
    // 需要记录所有日志的code列表(主要应用于track_log,便于排查问题).
    private $wholeLogCodes = array();

    // 初始化静态配置.
    public static function initConfigs($configs)
    {
        self::$configs = $configs;
    }

    /**
     * 根据appCode获取业务日志实例.
     *
     * @param string $appCode
     * @param array  $config
     * @return BizLog
     */
    public static function getInstance($appCode = 'default', $config = array())
    {
        if (empty($config) && isset(self::$configs[$appCode])) {
            $config = self::$configs[$appCode];
        }
        if (empty(self::$instances[$appCode])) {
            self::$instances[$appCode] = new self($config);
        }

        return self::$instances[$appCode];
    }

    protected function __construct($config = array())
    {
        if (!empty($config)) {
            foreach ($config as $attr => $value) {
                if (property_exists(__CLASS__, $attr)) {
                    $this->{$attr} = $value;
                }
            }
        }

        if (!in_array($this->suffix, array(self::SUFFIX_TYPE_MONTH, self::SUFFIX_TYPE_DAY))) {
            $this->suffix = self::SUFFIX_TYPE_DAY;
        }

        // 最多分20个子文件.
        if ($this->mod > 20) {
            $this->mod = 20;
        }

    }

    /**
     * 跟踪日志,默认不记录,若记录需更改日志配置,具体参看checkNeedLog逻辑.
     *
     * @param       $message
     * @param       $target
     * @param       $category
     * @param       $requestData
     * @param       $result
     * @param array $params
     * @return bool
     */
    public static function trackLog($message, $target, $category, $requestData, $result = '', $params = array())
    {
        $params['type'] = self::LOG_TYPE_TRACK;
        $appCode = ArrayHelper::getValue($params, 'appCode', self::APP_CODE_DEFAULT);
        return self::getInstance($appCode)->log($message, $target, $category, $requestData, $result, $params);
    }

    /**
     * 错误或失败日志,总是记录.
     *
     * @param       $message
     * @param null  $target
     * @param       $category
     * @param       $requestData
     * @param       $result
     * @param int   $code
     * @param array $params
     * @return bool
     */
    public static function errorLog($message, $target = null, $category, $requestData, $result, $code = 0, $params = array())
    {
        $params['type'] = self::LOG_TYPE_ERROR;
        $params['code'] = $code;
        $appCode = ArrayHelper::getValue($params, 'appCode', self::APP_CODE_DEFAULT);
        return self::getInstance($appCode)->log($message, $target, $category, $requestData, $result, $params);
    }

    /**
     * 数据变更日志,总是记录.
     *
     * @param        $message
     * @param        $target
     * @param        $category
     * @param        $changeData
     * @param string $requestData
     * @param array  $params
     * @return bool
     */
    public static function dataChangeLog($message, $target, $category, $changeData, $requestData = '', $params = array())
    {
        $params['type'] = self::LOG_TYPE_DATA_CHANGE;
        $appCode = ArrayHelper::getValue($params, 'appCode', self::APP_CODE_DEFAULT);
        return self::getInstance($appCode)->log($message, $target, $category, $requestData, $changeData, $params);
    }

    /**
     * 整理日志数据并保存在规定路径.
     *
     * @param         $message
     * @param mixed   $target
     * @param string  $category
     * @param mixed   $requestData
     * @param mixed   $resultData
     * @param array   $params
     * @return bool
     */
    protected function log($message, $target = null, $category = 'log', $requestData = '', $resultData = '', $params = array())
    {

        // 检查是否需要记录日志.
        if (!$this->checkNeedLog($target, $message, $category, $params)) {
            return false;
        }

        $logData = array();
        try {
            $logData['message'] = $message;
            $logData['target'] = $target;
            $logData['category'] = $category;
            $logData['code'] = ArrayHelper::getValue($params, 'code', 0);
            $logData['type'] = ArrayHelper::getValue($params, 'type', '');
            $logData['request_data'] = $requestData;
            $logData['result_data'] = $resultData;

            $logData['username'] = ArrayHelper::getValue($params, 'username', 'system');
            $logData['username_full'] = ArrayHelper::getValue($params, 'username_full', 'system');

            $timeMicro = microtime(true);
            $logData['log_time'] = date("Y-m-d H:i:s", intval($timeMicro));
            $logData['log_time_micro'] = $timeMicro;

            $ipAddress = ArrayHelper::getValue($params, 'ip_address', @ServerHelper::getClientIp());
            $ipAddress = empty($ipAddress) ? gethostname() : $ipAddress;
            $logData['client_ip'] = $ipAddress;

            $logData['query_key'] = implode('_', array($logData['target'], $logData['category'], $logData['type'], $logData['code']));
            $logData['id'] = self::generateId($logData);

            // 获取log存储的文件夹
            $logFile = self::getLogFile(intval($timeMicro));

            $logDataJson = ArrayHelper::arrayToJsonFormat($logData);

            FileHelper::save($logDataJson, $logFile);

        } catch (\Exception $e) {
            return $e->getMessage();
        }

        return true;
    }

    /**
     * 判断是否需要记录日志.
     *
     * @param $target
     * @param $message
     * @param $category
     * @param $params
     * @return bool
     */
    protected function checkNeedLog($target, $message, $category, $params = array())
    {

        // 若未配置启用日志,则返回false
        if (!$this->isEnable) {
            return false;
        }
        // 如果传入了日志类型且类型属于错误或者数据变更则记录日志.
        if (!empty($params['type']) && in_array($params['type'], array(self::LOG_TYPE_ERROR, self::LOG_TYPE_DATA_CHANGE))) {
            return true;
        }
        if (!empty($this->wholeLogCategories)) {
            // 如果配置了记录完整日志的分类列表,且列表包含ALL或者该条日志的分类,则记录日志.
            if (in_array('ALL', $this->wholeLogCategories) || in_array($category, $this->wholeLogCategories)) {
                return true;
            }
        }
        if (!empty($target) && !empty($this->wholeLogTargets)) {
            // 如果配置了记录完整日志的target列表,且列表包含该条日志的target,则记录日志.
            if (in_array($target, $this->wholeLogTargets)) {
                return true;
            }
        }
        if (!empty($params['code'])) {
            if (!empty($this->wholeLogCodes)) {
                // 如果配置了记录完整日志的code列表,且列表包含该条日志的code,则记录日志.
                if (in_array($params['code'], $this->wholeLogCodes)) {
                    return true;
                }
            }
        }
        $needLog = false;
        // 若日志信息中包含异常/失败/错误等词汇,则记录日志.
        array_map(function ($value) use ($message, &$needLog) {
            $message = strtolower($message);
            if (strpos($message, $value) !== false) {
                $needLog = true;
            }
        }, array('异常', '失败', '错误', 'exception', 'error', 'fail'));

        return $needLog;
    }


    /**
     * 生成唯一ID
     *
     * @param $logData
     * @return string
     */
    protected function generateId($logData)
    {
        $list = array(
            'appCode' => $this->appCode,
            'data' => $logData,
            'time' => microtime(),
            'rand' => rand(1, 10000),
            'host' => gethostname()
        );
        $id = md5(ArrayHelper::arrayToJsonFormat($list));

        return $id;
    }

    /**
     * 获取日志文件路径.
     *
     * @param integer $time
     * @return string
     * @throws \Exception
     */
    protected function getLogFile($time = 0)
    {
        // 获取log存储的文件夹
        $logFile = $dir = $this->dir;

        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
            chmod($dir, 0777);
        }

        // 获取log存储的文件名前缀
        $logFile .= "/" . $this->prefix;

        if (empty($time)) {
            $time = time();
        }
        $logTime = $time;

        // 生成文件名后缀
        switch ($this->suffix) {
            case self::SUFFIX_TYPE_DAY :
                $logFile .= "_" . date("Ymd", $logTime);
                break;
            case self::SUFFIX_TYPE_MONTH :
                $logFile .= "_" . date("Ym", $logTime);
                break;
        }

        $modTail = '';
        if ($this->mod > 1) {
            $modTail = "01";
            if (preg_match("/\.(\d+)$/", microtime(true), $matches)) {
                $modTail = str_pad($matches[1] % $this->mod, 2, 0, STR_PAD_LEFT);
            }
        }

        if (!empty($modTail)) {
            $logFile .= "_{$modTail}";
        }
        $logFile .= "." . $this->ext;

        return $logFile;
    }

}