<?php
/**
 * Created by PhpStorm.
 * User: guokeling
 * Date: 2018/12/26
 * Time: 11:01
 */

namespace PHPHelper\helpers;

/**
 * Class FileHelper
 *
 * @package PHPHelper\helpers
 */
class FileHelper
{
    /**
     * 将内容写入文件.
     *
     * @param string $data
     * @param string $file
     * @throws \Exception
     */
    public static function save($data = '', $file)
    {
        // 如果文件不存在则创建
        if (!file_exists($file)) {
            touch($file);
            chmod($file, 0777);
        }

        $result = file_put_contents($file, $data . "\n", FILE_APPEND | LOCK_EX);

        return $result;
    }
}