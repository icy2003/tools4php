<?php

/**
 * 树
 *
 * @namespace 
 * @filename Tree.php
 * @encoding UTF-8
 * @author forsona <2317216477@qq.com>
 * @link https://github.com/forsona
 * @datetime 2016-11-5 9:15:13
 * @php 5.3+
 * @version $Id$
 */
class Tree
{

    /**
     * 对一棵树从上到下排序
     * @param array $data 普通的二维数组（可能是从数据库新鲜出炉的）
     * @param string $id 数组中的ID字段，默认为'id'
     * @param string $pid 数组中的父ID字段，默认为'pid'
     * @param string $rootPid 根节点的父ID，默认为'0'
     * @return array 
     */
    public static function orderTreeUpDown($data, $id = 'id', $pid = 'pid', $rootPid = '0')
    {
        //把ID作为键重排数组
        $temp = array();
        foreach ($data as $row) {
            $temp[$row[$id]] = $row;
        }
        //把父ID作为键，相同父ID的节点列表作为值重排数组
        $array = array();
        foreach ($temp as $id => $row) {
            //如果某个节点的父ID不存在已有的ID里，就设置成0
            if (!isset($temp[$row[$pid]])) {
                $row[$pid] = $rootPid;
            }
            $array[$row[$pid]][$id] = $row;
        }
        $return = array();
        $pidArray = array($rootPid);
        //循环筛选
        while (1) {
            $temp = array();
            foreach ($pidArray as $pidRow) {
                $row = isset($array[$pidRow]) ? $array[$pidRow] : array();
                $return += $row;
                $temp = array_merge($temp, array_keys($row));
                unset($array[$pidRow]);
            }
            $pidArray = $temp;
            if (empty($array)) {
                break;
            }
        }
        return $return;
    }

}
