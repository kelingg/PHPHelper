<?php
/**
 * Created by PhpStorm.
 * User: guokeling
 * Date: 2018/12/24
 * Time: 10:24
 */

require_once __DIR__ . '/../Autoload.php';

echo '2 的 32 次方:' . pow(2, 32) . "\n";
$server1 = '127.0.0.1';
$server2 = '127.0.0.2';
$hash1 = \PHPHelper\helpers\HashHelper::getHashByTime33($server1);
$hash2 = \PHPHelper\helpers\HashHelper::getHashByTime33($server2);
echo 'server1' . ' hash:' . $hash1 . "\n";
echo 'server2' . ' hash:' . $hash2 . "\n";

$hashObj = new \PHPHelper\libs\ConsistentHash();

$hashObj->setVirtualNum(10);
$hashObj->addNode($server1);
$hashObj->addNode($server2);
$hashObj->sortRingList();

print_r($hashObj->getRingList());
print_r($hashObj->getNodes());

$keyPrefix = 'user_';
for($i=0;$i<10;$i++){
    $key = $keyPrefix . ($i + 100);
    echo $key . ' hash:' . \PHPHelper\helpers\HashHelper::getHashByTime33($key) . '=> '. $hashObj->getNode($key) . "\n";
}