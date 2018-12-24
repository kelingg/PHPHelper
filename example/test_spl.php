<?php
/**
 * Created by PhpStorm.
 * User: guokeling
 * Date: 2018/12/21
 * Time: 17:40
 */

class a{}
class a1 extends a{}

echo 'class a1`s parent is:';
print_r(class_parents(new a1));
echo "\n";

echo 'class a1`s object_id is:';
print_r(spl_object_hash(new a1));
echo "\n";

$list = new ArrayIterator(array('a' => 'b', 'c', 'd', 'e'));
var_dump(iterator_count($list));
var_dump(iterator_to_array($list));

$list = new SplDoublyLinkedList();
$list->push('a');
$list->push('bdd');
$list->push('cg');
$list->push('dggde');

$list->unshift('top');
$list->shift();

$list->rewind();//rewind操作用于把节点指针指向Bottom所在的节点
$list->next();
echo 'curren node:'.$list->current()."\n";//获取当前节点
echo 'curren node:'.$list->count()."\n";//获取节点数
echo 'curren node:'.$list->key()."\n";//获取节点数
var_dump($a = $list->serialize());

$stack = new SplStack();
$stack->push('a');
$stack->push('c');
$stack->push('d');
$stack->push('e');
$stack->push('f');

$stack->rewind();
echo 'curren node:'.$stack->current()."\n";//获取当前节点
$stack->pop();
$stack->rewind();
echo 'curren node:'.$stack->current()."\n";//获取当前节点

$queue = new SplQueue();
$queue->enqueue('c1');
$queue->enqueue('c2');
$queue->enqueue('c3');
$queue->enqueue('c4');

$queue->rewind();
echo 'curren node:'.$queue->current()."\n";//获取当前节点
$queue->next();
$queue->offsetUnset(2);
echo 'curren node:'.$queue->current()."\n";//获取当前节点
$queue->next();
echo 'curren node:'.$queue->current()."\n";//获取当前节点


$priorityQueue = new SplPriorityQueue();
$priorityQueue->insert('a1', 3);
$priorityQueue->insert('a2', 2);
$priorityQueue->insert('a3', 1);
$priorityQueue->insert('a4', 1);
$priorityQueue->insert('a5', 6);
$priorityQueue->insert('a6', 8);
echo "priorityQueue count is:";
echo $priorityQueue->count() . "\n";
echo "priorityQueue curren node:";
echo $priorityQueue->current() . "\n";
while($priorityQueue->valid()){
    echo "priorityQueue curren node:" . $priorityQueue->current() . "\n";
    $priorityQueue->next();
}

$heap = new SplMaxHeap();

$heap->insert(1);
$heap->insert(6);
$heap->insert(12);
$heap->insert(12);
$heap->insert(3);
$heap->insert(4);
$heap->insert(67);
$heap->insert(13);

echo "heap top:" . $heap->top()."\n";
echo "heap count:" . $heap->count()."\n";
$heap->next();
echo "heap current:" . $heap->current()."\n";
foreach($heap as $item) {
    echo $item . "\n";
}

$dir = new DirectoryIterator(__DIR__);
foreach($dir as $file) {
    if($file->isDot()){
        echo "file is dot:" . $file->getBasename()."\n";
        continue;
    }
    echo "file name:" . $file->getBasename()."\n";

}

$list = array(
    'a' => 'a1',
    'b' => 'b1',
    'c' => 'c1',
    'd' => 'd1',
    'e' => 'e1',
);
$obj = new ArrayObject($list);
$it = $obj->getIterator();
while($it->valid()){
    echo $it->key() . '=> '. $it->current()."\n";
    $it->next();
}