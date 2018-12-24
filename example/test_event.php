<?php
/**
 * Created by PhpStorm.
 * User: guokeling
 * Date: 2018/12/24
 * Time: 16:23
 */

require_once __DIR__ . '/../Autoload.php';

\PHPHelper\libs\Event::attach('walk', function () {
    echo "I am walking!……\n";
});

\PHPHelper\libs\Event::attach('walk', function () {
    echo "I am listening!……\n";
}, true);

\PHPHelper\libs\Event::trigger('walk');
\PHPHelper\libs\Event::trigger('walk');

\PHPHelper\libs\Event::attachOne('sing', function ($song) {
    echo "I am sing " . $song . "……\n";
});

\PHPHelper\libs\Event::trigger('sing', '简单爱');
\PHPHelper\libs\Event::trigger('sing', '一生平安');

class Person
{
    protected $name = '';

    public function __construct($name = '')
    {
        $this->name = $name;
    }

    public function walk()
    {
        echo $this->name . " is walking ……!\n";
    }

    public function sing()
    {
        echo $this->name . " is singing " . \PHPHelper\helpers\ArrayHelper::arrayToJsonFormat(func_get_args()) . " ……!\n";
    }
}

$person = new Person('刘德华');

\PHPHelper\libs\Event::attach('person_walk', array($person, 'walk'));
\PHPHelper\libs\Event::trigger('person_walk');

\PHPHelper\libs\Event::attach('person_sing', array($person, 'sing'));
\PHPHelper\libs\Event::trigger('person_sing', '冰雨', '中国人', '来生缘');