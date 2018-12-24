<?php

/**
 * Created by PhpStorm.
 * User: guokeling
 * Date: 2018/12/24
 * Time: 11:20
 */

namespace PHPHelper\libs;

class ConsistentHash
{
    // 节点hash之后的圆环列表.
    protected $ringList = array();
    // 添加过的节点列表.
    protected $nodes = array();
    // 圆环列表是否有序.
    protected $isSort = false;
    // 每个节点的虚拟节点.
    protected $virtualNum = 64;

    public function __construct($nodes = array())
    {
        $this->addNodes($nodes);
    }

    /**
     * 增加节点.
     *
     * @param $node
     * @return $this|bool
     */
    public function addNode($node)
    {
        if (isset($this->nodes[$node])) {
            return false;
        }

        for ($i = 0; $i < $this->virtualNum; $i++) {
            $hash = $this->getHash($node . '_' . $i);
            $this->ringList[$hash] = $node;
            $this->nodes[$node][] = $hash;
        }
        $this->isSort = false;
        return $this;
    }

    /**
     * 批量增加节点.
     *
     * @param $nodes
     * @return $this|bool
     */
    public function addNodes($nodes)
    {
        if (!empty($nodes)) {
            foreach ($nodes as $node) {
                $this->addNode($node);
            }
        }

        return $this;
    }

    /**
     * 删除节点.
     *
     * @param $node
     * @return $this|bool
     */
    public function removeNode($node)
    {
        if (!isset($this->nodes[$node])) {
            return false;
        }
        foreach ($this->nodes[$node] as $hash) {
            unset($this->ringList[$hash]);
        }
        unset($this->nodes[$node]);

        return $this;
    }

    /**
     * 获取key哈希之后命中的节点
     *
     * @param $key
     * @return bool
     */
    public function getNode($key)
    {
        $this->sortRingList();
        $hashKey = $this->getHash($key);
        $hashList = array_keys($this->ringList);
        $len = count($hashList);
        if ($len == 0) {
            return false;
        }
        if ($hashKey < $hashList[0] || $hashKey > $hashList[$len - 1]) {
            return $this->ringList[$hashList[$len - 1]];
        }
        foreach ($hashList as $id => $hashNode) {
            if (isset($hashList[$id + 1])) {
                $nextHashNode = $hashList[$id + 1];
            } else {
                return $this->ringList[$hashNode];
            }
            if ($hashKey >= $hashNode && $hashKey <= $nextHashNode) {
                return $this->ringList[$hashNode];
            }
        }
        return $this->ringList[$hashList[$len - 1]];
    }

    /**
     * 设置每个节点虚拟节点个数.
     *
     * @param int $virtualNum
     */
    public function setVirtualNum($virtualNum = 64)
    {
        if ($virtualNum > 0) {
            $this->virtualNum = $virtualNum;
        }
    }

    /**
     * 对节点圆环列表进行排序.
     */
    public function sortRingList()
    {
        if (!$this->isSort) {
            ksort($this->ringList);
            $this->isSort = true;
        }
    }

    /**
     * 获取节点圆环列表.
     *
     * @return array
     */
    public function getRingList()
    {
        return $this->ringList;
    }

    /**
     * 获取节点包含的hash值.
     *
     * @return array
     */
    public function getNodes()
    {
        return $this->nodes;
    }

    /**
     * 获取hash值.
     *
     * @param $key
     * @return int
     */
    public function getHash($key)
    {
        return \PHPHelper\helpers\HashHelper::getHashByTime33($key);
    }

}