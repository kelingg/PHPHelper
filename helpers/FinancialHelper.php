<?php
/**
 * Created by PhpStorm.
 * User: wind
 * Date: 2022/4/1
 * Time: 15:36
 */

namespace PHPHelper\helpers;


class FinancialHelper
{

    /**
     * 计算最大回撤, 并返回最大回撤和区间
     *
     * @param $sortItems array("20220101"=>array("market_value" => 101.20),……,"20220105"=>array("market_value" => 150.50))
     * @return array
     */
    public static function getMaxDrawdown($sortItems)
    {

        $days = array();
        $marketValues = array();
        // 遍历取出日期和每天市值列表
        foreach ($sortItems as $day => $item) {
            $days[] = $day;
            $marketValues[] = $item['market_value'];
        }

        $minMarketValues = array();
        $minDates = array();
        $tempMinMarketValue = null;
        $minDate = null;

        $marketValueCount = count($marketValues);
        // 从后往前遍历市值列表, 计算每一天在当天之后的最小市值.
        for ($i = -1; $i > -$marketValueCount - 1; $i--) {
            $index = $marketValueCount + $i;
            $currentValue = $marketValues[$index];
            $currentDay = $days[$index];
            if (is_null($tempMinMarketValue) or $currentValue < $tempMinMarketValue) {
                $tempMinMarketValue = $currentValue;
                $minDate = $currentDay;
            }
            $minMarketValues[] = $tempMinMarketValue;
            $minDates[] = $minDate;

        }
        $minMarketValues = array_reverse($minMarketValues);
        $minDates = array_reverse($minDates);
        $maxDrawdownValue = 0;
        $region = array();
        $i = 0;
        // 从前往后遍历, 计算当天买入在之后的最大回撤, 然后得出区间内最大回撤.
        foreach ($marketValues as $marketValue) {
            $drawdown = ($marketValue - $minMarketValues[$i]) / $marketValue;
            if ($maxDrawdownValue < $drawdown) {
                $maxDrawdownValue = $drawdown;
                $region = array(array($days[$i], round($marketValue, 2)), array($minDates[$i], round($minMarketValues[$i], 2)));
            }
            $i++;
        }

        return array($maxDrawdownValue, $region);

    }
}