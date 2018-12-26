<?php
/**
 * Created by PhpStorm.
 * User: guokeling
 * Date: 15/11/8
 * Time: 18:31
 */

namespace common\helpers;

/**
 * Class UserAgentHelper
 *
 * @package common\helpers
 */
class UserAgentHelper
{

    const BROWSER_CHROME = 'Chrome';

    public static $platformOptions = [
        'android' => 'android',
        'iPhone' => 'iPhone',
        'iPad' => 'iPad',
        'iPod' => 'iPod',
    ];

    public static function getPlatform($userAgent)
    {
        if (stripos($userAgent, 'android')) {
            $platForm = 'Android';
        } elseif (stripos($userAgent, 'iPhone')) {
            $platForm = 'iPhone';
        } elseif (stripos($userAgent, 'iPad')) {
            $platForm = 'iPad';
        } elseif (stripos($userAgent, 'iPod')) {
            $platForm = 'iPod';
        } elseif (stripos($userAgent, 'Windows Phone')) {
            $platForm = 'WindowsPhone';
        } else {
            $platForm = 'pc';
        }
        return $platForm;
    }

    public static function getSystem($userAgent)
    {
        $system = '';
        if (stripos($userAgent, 'android')) {
            $system = 'Android';
        } elseif (stripos($userAgent, 'iphone') || stripos($userAgent, 'iPad') || stripos($userAgent, 'iPod')) {
            $system = 'Ios';
        } elseif (stripos($userAgent, 'Windows Phone')) {
            $system = 'WindowsPhoneOS';
        } elseif (preg_match('/win 9x/i', $userAgent) && strpos($userAgent, '4.90')) {
            $system = "Windows ME";
        } elseif (preg_match('/win/i', $userAgent)) {
            if (strpos($userAgent, '95')) {
                $system = "Windows 95";
            } elseif (preg_match('/98/', $userAgent)) {
                $system = "Windows 98";
            } elseif (preg_match('/nt 5.0/i', $userAgent)) {
                $system = "Windows 2000";
            } elseif (preg_match('/nt 5.1/i', $userAgent)) {
                $system = "Windows XP";
            } elseif (preg_match('/nt 6.0/i', $userAgent)) {
                $system = "Windows Vista";
            } elseif (preg_match('/nt 6.1/i', $userAgent)) {
                $system = "Windows 7";
            } elseif (preg_match('/nt 6.2/i', $userAgent)) {
                $system = "Windows 8";
            } elseif (preg_match('/nt 6.3/i', $userAgent)) {
                $system = "Windows 8.1";
            } elseif (preg_match('/nt 6.4/i', $userAgent)) {
                $system = "Windows 10";
            } elseif (preg_match('/nt 10.0/i', $userAgent)) {
                $system = "Windows 10";
            } elseif (preg_match('/nt/i', $userAgent)) {
                $system = "Windows NT";
            } else {
                $system = "Windows";
            }
        } elseif (preg_match('/Mac OS/i', $userAgent)) {
            $system = "Mac OS";
        } elseif (preg_match('/linux/i', $userAgent)) {
            $system = "Linux";
        } elseif (preg_match('/unix/i', $userAgent)) {
            $system = "Unix";
        } elseif (preg_match('/sun/i', $userAgent) && preg_match('/os/i', $userAgent)) {
            $system = "SunOS";
        } elseif (preg_match('/ibm/i', $userAgent) && preg_match('/os/i', $userAgent)) {
            $system = "IBM OS/2";
        } elseif (preg_match('/Mac/i', $userAgent) && preg_match('/PC/i', $userAgent)) {
            $system = "Macintosh";
        } elseif (preg_match('/PowerPC/i', $userAgent)) {
            $system = "PowerPC";
        } elseif (preg_match('/AIX/i', $userAgent)) {
            $system = "AIX";
        } elseif (preg_match('/HPUX/i', $userAgent)) {
            $system = "HPUX";
        } elseif (preg_match('/NetBSD/i', $userAgent)) {
            $system = "NetBSD";
        } elseif (preg_match('/BSD/i', $userAgent)) {
            $system = "BSD";
        } elseif (preg_match('OSF1/', $userAgent)) {
            $system = "OSF1";
        } elseif (preg_match('IRIX/', $userAgent)) {
            $system = "IRIX";
        } elseif (preg_match('/FreeBSD/i', $userAgent)) {
            $system = "FreeBSD";
        }
        if ($system == '') {
            $system = "Unknown";
        }
        return $system;
    }

    public static function getBrowser($userAgent)
    {
        if (preg_match('/MSIE ([0-9].[0-9]{1,2})/', $userAgent, $version)) {
            $version = $version[1];
            $browser = "IE";
        } else {
            if (preg_match('/Trident\/7.0; rv:11.0/', $userAgent)) {
                $version = '11';
                $browser = "IE";
            } elseif (preg_match('/baiduboxapp\/([0-9_.]{1,7})/', $userAgent, $version)) {
                $version = $version[1];
                $browser = "BaiDuApp";
            } elseif (preg_match('/MQQBrowser\/([0-9_.]{1,3})/', $userAgent, $version)) {
                $version = $version[1];
                $browser = "QQBrowser";
            } elseif (preg_match('/MicroMessenger\/([0-9_.]{1,5})/', $userAgent, $version)) {
                $version = $version[1];
                $browser = "WeiXin";
            } elseif (preg_match('/Opera\/([0-9]{1,2}.[0-9]{1,2})/', $userAgent, $version)) {
                $version = $version[1];
                $browser = "Opera";
            } elseif (preg_match('/Firefox\/([0-9.]{1,5})/', $userAgent, $version)) {
                $version = $version[1];
                $browser = "Firefox";
            } elseif (preg_match('/Chrome\/([0-9.]{1,4})/', $userAgent, $version)) {
                $version = $version[1];
                $browser = self::BROWSER_CHROME;
            } elseif (preg_match('/UCBrowser\/([0-9.]{1,4})/', $userAgent, $version)) {
                $version = $version[1];
                $browser = "UCBrowser";
            } elseif (preg_match('/Safari\/([0-9.]{1,5})/', $userAgent, $version)) {
                $version = $version[1];
                $browser = "Safari";
            } else {
                $version = "";
                $browser = "Unknown";
            }
        }
        return [$browser, $version];
    }

    public static function getSpider($userAgent)
    {
        if (stristr($userAgent, 'bingbot')) {
            $spider = 'BingBot';
        } elseif (stristr($userAgent, 'DotBot')) {
            $spider = 'DotBot';
        } elseif (stristr($userAgent, 'Googlebot')) {
            $spider = 'GoogleBot';
        } elseif (stristr($userAgent, 'MJ12bot')) {
            $spider = 'MJ12Bot';
        } elseif (stristr($userAgent, 'msnbot')) {
            $spider = 'MsnBot';
        } elseif (stristr($userAgent, 'Sosospider')) {
            $spider = 'SoSoSpider';
        } elseif (stristr($userAgent, 'Baiduspider')) {
            $spider = 'BaiDuSpider';
        } elseif (stristr($userAgent, 'Sogou web spider')) {
            $spider = 'SogouSpider';
        } elseif (stristr($userAgent, 'Riddler')) {
            $spider = 'Riddler';
        } elseif (stristr($userAgent, 'Alibaba.Security')) {
            $spider = 'AliYun';
        } else {
            $spider = false;
        }
        return $spider;
    }

} 