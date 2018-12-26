<?php
/**
 * Created by PhpStorm.
 * User: guokeling
 * Date: 2018/12/24
 * Time: 16:12
 */

namespace PHPHelper\libs;

/**
 * Class Event,事件类.
 *
 * @package PHPHelper\libs
 */
class Event
{
    protected static $events = array();

    public static function attach($event, $callback, $once = false)
    {
        if (!is_callable($callback)) {
            return false;
        }
        self::$events[$event][] = array(
            'callback' => $callback,
            'once' => $once
        );
        return true;
    }

    public static function attachOne($event, $callback)
    {
        return self::attach($event, $callback, true);
    }

    public static function detach($event, $index = null)
    {
        if (is_null($index)) {
            unset(self::$events[$event]);
        } else {
            unset(self::$events[$event][$index]);
        }
    }

    public static function trigger()
    {
        if (!func_num_args()) {
            return null;
        }
        $args = func_get_args();
        $event = array_shift($args);
        if (!isset(self::$events[$event])) {
            return false;
        }
        foreach (self::$events[$event] as $index => $value) {
            $callback = $value['callback'];
            if ($value['once']) {
                self::detach($event, $index);
            }
            call_user_func_array($callback, $args);
        }
        return true;
    }

}