<?php
/**
 * Created by PhpStorm.
 * User: guokeling
 * Date: 2018/12/24
 * Time: 10:24
 */

require_once __DIR__ . '/../Autoload.php';


try {
//    print("add: " . \PHPHelper\helpers\CityHash::smartAdd(10, 20, 30, 40) . "\n");
//    print("md5:" . md5(rand(10000000, 1000000000)) . "\n");
//    & 0x0FFFFFFFF
    $testStr = "5df54e31216a3ca905a1799919d92ad7f40955a3f13864202a9092946635dddc77eae2e2386309b8a1980fbdcd2480b082a99d2cf8791e144047d2c645c9bd7511efba14a31a3d91cf5593cf000635d73a00db33ef80f19977c1631f3ad3d5b1ead5542371cc2d93bb6bd8a7720d8d669609b51192eab0fe4a15815c072f861c2a082d84368c5baca9dd5f1d310ef12e";
    // 简易调用方式, 使用HashHelper.
    print("hash32: " . (\PHPHelper\helpers\HashHelper::getHashByCity32($testStr)) . "\n");

    // 测试不同长度字符串不同hash算法的结果是否正确.
    $list = array(4, 8, 12, 16, 24, 32, 64, 128, 256);
    foreach ($list as $len) {
        print("===========" . $len . "===========\n");
        $param = substr($testStr, 11, $len);
        print("param: " . $param . "\n");
        $bytes = \PHPHelper\helpers\StringHelper::getBytes($param);
        print("hash32: " . (\PHPHelper\libs\CityHash::hash32($bytes)) . "\n");
        print("hash64: " . (\PHPHelper\libs\CityHash::hash64($bytes)) . "\n");
        print("hash128_low: " . (\PHPHelper\libs\CityHash::hash128($bytes)->getLowValue()) . "\n");
        print("hash128_hig: " . (\PHPHelper\libs\CityHash::hash128($bytes)->getHighValue()) . "\n");
        // 若保留32位数字, 使用以下方式
        print("只保留32位数字: \n");
        print("hash32: " . (\PHPHelper\helpers\HashHelper::getHashByCity32($param)) . "\n");
        print("hash32: " . (\PHPHelper\libs\CityHash::hash32($bytes) & 0x0FFFFFFFF) . "\n");
        print("hash64: " . (\PHPHelper\libs\CityHash::hash64($bytes) & 0x0FFFFFFFF) . "\n");
        print("hash128_low: " . (\PHPHelper\libs\CityHash::hash128($bytes)->getLowValue() & 0x0FFFFFFFF) . "\n");
        print("hash128_hig: " . (\PHPHelper\libs\CityHash::hash128($bytes)->getHighValue() & 0x0FFFFFFFF) . "\n");

    }
} catch (Exception $e) {
    print($e->getMessage());
}