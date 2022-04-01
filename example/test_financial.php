<?php
/**
 * Created by PhpStorm.
 * User: guokeling
 * Date: 2018/12/10
 * Time: 19:10
 */

require_once __DIR__ . '/../Autoload.php';

$sortItems = array();
$sortItems['20220101'] = array('market_value' => 650);
$sortItems['20220102'] = array('market_value' => 1000);
$sortItems['20220103'] = array('market_value' => 150);
$sortItems['20220104'] = array('market_value' => 320);
$sortItems['20220105'] = array('market_value' => 400);
$sortItems['20220106'] = array('market_value' => 260);
$sortItems['20220107'] = array('market_value' => 500);
$sortItems['20220108'] = array('market_value' => 150);
$sortItems['20220109'] = array('market_value' => 160);
$sortItems['20220110'] = array('market_value' => 200);
$sortItems['20220111'] = array('market_value' => 550);
$sortItems['20220112'] = array('market_value' => 320);
$sortItems['20220113'] = array('market_value' => 460);
$sortItems['20220114'] = array('market_value' => 50);
$sortItems['20220115'] = array('market_value' => 280);

try {
    foreach($sortItems as $day => $item){
        print $day;
        print "\t";
        print $item['market_value'];
        print "\n";
    }
    $result = \PHPHelper\helpers\FinancialHelper::getMaxDrawdown($sortItems);
    print_r($result);
} catch (Exception $e) {
    echo $e->getMessage();
}
