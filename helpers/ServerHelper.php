<?php

namespace PHPHelper\helpers;

/**
 * Created by PhpStorm.
 * User: guokeling
 * Date: 2018/12/10
 * Time: 19:11
 */
class ServerHelper
{
    /**
     * 获取客户端ip.
     *
     * @return string.
     */
    public static function getClientIp()
    {
        if (isset($_SERVER["HTTP_X_FORWARDED_FOR"]) && !empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            $ipAddress = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ipAddress = $_SERVER['REMOTE_ADDR'];
        } elseif (getenv("REMOTE_ADDR")) {
            $ipAddress = getenv("REMOTE_ADDR");
        } elseif (getenv("HTTP_CLIENT_IP")) {
            $ipAddress = getenv("HTTP_CLIENT_IP");
        } else {
            $ipAddress = "";
        }
        return $ipAddress;
    }

    /**
     * 获取服务端ip.
     *
     * @return string.
     */
    public static function getServerIp()
    {
        $ipAddress = '';
        if (isset($_SERVER)) {
            if (isset($_SERVER['SERVER_ADDR'])) {
                $ipAddress = $_SERVER['SERVER_ADDR'];
            } elseif (isset($_SERVER['LOCAL_ADDR'])) {
                $ipAddress = $_SERVER['LOCAL_ADDR'];
            }
        }
        if (empty($ipAddress)) {
            $ipAddress = getenv('SERVER_ADDR');
        }

        return $ipAddress;
    }

}