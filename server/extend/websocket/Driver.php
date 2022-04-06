<?php

// [ SocketSDK ]
// @wiki http://doc2.workerman.net
// @import use websocket\Driver as WebSocket;

namespace websocket;
use GatewayWorker\Lib\Gateway;

class Driver
{
    // 向所有客户端或者$client_id_array指定的客户端发送$send_data数据
    public static function sendToAll($send_data, $client_id_arr = null, $exclude_client_id_arr = null, $raw = false)
    {
        Gateway::sendToAll($send_data,$client_id_arr,$exclude_client_id_arr,$raw);
    }

    // 向客户端$client_id发送$send_data数据
    public static function sendToClient($client_id, $send_data)
    {
        Gateway::sendToClient($client_id,$send_data);
    }

    // 断开与$client_id对应的客户端的连接
    public static function closeClient($client_id)
    {
        Gateway::closeClient($client_id);
    }

    // 判断$client_id是否还在线
    // 在线返回1，不在线返回0
    public static function isOnline($client_id)
    {
        return Gateway::isOnline($client_id);
    }

    // 将$client_id与$uid绑定
    public static function bindUid($client_id, $uid)
    {
        Gateway::bindUid($client_id,$uid);
    }

    // 将$client_id与$uid解绑
    public static function unbindUid($client_id, $uid)
    {
        Gateway::unbindUid($client_id,$uid);
    }

    // 判断$uid是否在线
    // 在线返回1，不在线返回0
    public static function isUidOnline($uid)
    {
        return Gateway::isUidOnline($uid);
    }

    // 根据$uid获取client_id集合
    // 返回一个数组，数组元素为与$uid绑定的所有在线的client_id
    // 如果没有在线的client_id则返回一个空数组
    public static function getClientIdByUid($uid)
    {
        return Gateway::getClientIdByUid($uid);
    }

    // 根据$client_id获取uid
    // 返回$client_id绑定的uid，如果$client_id没有绑定uid，则返回null
    public static function getUidByClientId($client_id)
    {
        return Gateway::getUidByClientId($client_id);
    }

    // 向$uid绑定的所有在线client_id发送$message数据
    public static function sendToUid($uid, $message)
    {
        Gateway::sendToUid($uid,$message);
    }

    // 将$client_id加入$group组
    public static function joinGroup($client_id, $group)
    {
        Gateway::joinGroup($client_id,$group);
    }

    // 将$client_id从$group组中删除
    public static function leaveGroup($client_id, $group)
    {
        Gateway::leaveGroup($client_id,$group);
    }

    // 解散分组
    public static function ungroup($group)
    {
        Gateway::ungroup($group);
    }

    // 向某个分组的所有在线client_id发送数据
    public static function sendToGroup($group, $message, $exclude_client_id_arr = null, $raw = false)
    {
        Gateway::sendToGroup($group,$message,$exclude_client_id_arr,$raw);
    }

    // 获取某分组当前在线成连接数
    public static function getClientIdCountByGroup($group)
    {
        return Gateway::getClientIdCountByGroup($group);
    }

    // 获取某个分组所有在线client_id信息
    public static function getClientSessionsByGroup($group)
    {
        return Gateway::getClientSessionsByGroup($group);
    }

    // 获取当前在线连接总数
    public static function getAllClientIdCount()
    {
        return Gateway::getAllClientIdCount();
    }

    // 获取当前所有在线client_id信息
    public static function getAllClientSessions()
    {
        return Gateway::getAllClientSessions();
    }

    // 设置某个$client_id对应的$session
    // $session一般为数组
    public static function setSession($client_id, $session)
    {
        Gateway::setSession($client_id,$session);
    }

    // 更新某个$client_id对应的$session
    public static function updateSession($client_id, $session)
    {
        Gateway::updateSession($client_id,$session);
    }

    // 获取某个$client_id对应的session
    public static function getSession($client_id)
    {
        return Gateway::getSession($client_id);
    }

    // 获取某个分组所有在线client_id列表
    public static function getClientIdListByGroup($group)
    {
        $list = Gateway::getClientIdListByGroup($group);
        return array_keys($list);
    }

    // 获取全局所有在线client_id列表
    public static function getAllClientIdList()
    {
        $list = Gateway::getAllClientIdList();
        return array_keys($list);
    }

    // 获取某个分组所有在线uid列表
    public static function getUidListByGroup($group)
    {
        $list = Gateway::getUidListByGroup($group);
        return array_keys($list);
    }

    // 获取某个分组下的在线uid数量
    public static function getUidCountByGroup($group)
    {
        return Gateway::getUidCountByGroup($group);
    }

    // 获取全局所有在线uid列表
    public static function getAllUidList()
    {
        $list = Gateway::getAllUidList();
        return array_keys($list);
    }

    // 获取全局所有在线uid数量
    public static function getAllUidCount()
    {
        return Gateway::getAllUidCount();
    }

    // 获取全局所有在线group列表
    public static function getAllGroupIdList()
    {
        $list = Gateway::getAllGroupIdList();
        return array_keys($list);
    }

}