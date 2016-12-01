<?php

/**
 * 树
 *
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
     * 对一棵树从上到下排序（按照这个顺序创建节点时，父节点一定已经创建好）
     * @param array $data 普通的二维数组（可能是从数据库新鲜出炉的）
     * @param string $id 数组中的ID字段，默认为'id'
     * @param string $pid 数组中的父ID字段，默认为'pid'
     * @param string $rootPid 根节点的父ID，默认为'0'
     * @return array 
     */
    public static function treeOrderUpDown($data, $id = 'id', $pid = 'pid', $rootPid = '0')
    {
        //把ID作为键重排数组
        $temp = array();
        foreach ($data as $row) {
            $temp[$row[$id]] = $row;
        }
        //把父ID作为键，相同父ID的节点列表作为值重排数组
        $array = array();
        foreach ($temp as $dataid => $row) {
            //如果某个节点的父ID不存在已有的ID里，就设置成0
            if (!isset($temp[$row[$pid]])) {
                $row[$pid] = $rootPid;
            }
            $array[$row[$pid]][$dataid] = $row;
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

    /**
     * 对id-pid的树的名字按照层级重写，如节点2的父节点是节点1，节点1的父节点是根节点，那么最后，节点2的名字会变成'节点1/节点2'
     * @param array $datas 树的数据
     * @param string $id ID字段，默认为'id'
     * @param string $pid 父ID字段，默认为'pid'
     * @param string $name 这里不一定是节点名字，含义为：指定层的name的节点是唯一的，由于一般是指名字，所以默认为'name'
     * @param string $rootPid 根节点的父ID，默认为'0'
     * @param string $delimiter 分隔符
     * @return array
     */
    public static function treeResetName($datas, $id = 'id', $pid = 'pid', $name = 'name', $rootPid = '0', $delimiter = '/')
    {
        $list = array();
        foreach ($datas as $data) {
            $list[$data[$id]] = $data;
        }
        $temp = $list;
        while (1) {
            $break = false;
            $i = 0;
            foreach ($temp as $dataid => $data) {
                //对根节点以及根节点下的节点，name不做处理
                if ($dataid != $rootPid && $data[$pid] != $rootPid) {
                    $temp[$dataid][$name] = $list[$data[$pid]][$name] . $delimiter . $temp[$dataid][$name];
                    $temp[$dataid][$pid] = $list[$data[$pid]][$pid];
                } else {
                    $i++;
                }
                if ($i == count($temp)) {
                    $break = true;
                }
            }
            if (true === $break) {
                break;
            }
        }
        return $temp;
    }

}
