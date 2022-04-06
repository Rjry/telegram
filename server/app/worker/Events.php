<?php
/**
* 用于检测业务代码死循环或者长时间阻塞等问题
* 如果发现业务卡死，可以将下面declare打开（去掉//注释），并执行php start.php reload
* 然后观察一段时间workerman.log看是否有process_timeout异常
*/
// declare(ticks=1);

namespace app\worker;
use GatewayWorker\Lib\Gateway;
use Workerman\Worker;
use Workerman\Lib\Timer;
use app\api\lib\Jwt;

use app\worker\events\GetNowBlock as GetNowBlockEvent;
use app\worker\events\GetBlockTrans as GetBlockTransEvent;
use app\worker\events\GapPltTransLog as GapPltTransLogEvent;
use app\worker\events\CalDepositOrder as CalDepositOrderEvent;

class Events
{
    /**
     * 当businessWorker进程启动时触发，每个进程生命周期内都只会触发一次
     * 可以在这里为每一个businessWorker进程做一些全局初始化工作，例如设置定时器，初始化redis等连接等。
     * @param  [object] $businessWorker businessWorker进程实例
     */
    public static function onWorkerStart($businessWorker)
    {
        /*连接*/
        // \channel\Client::connect('0.0.0.0', 2206);


        /*模板*/
        // \channel\Client::on('xxx', function($data) {

        //     $type = $data['type'];
        //     $data = $data['data'];
            
        //     if ( Gateway::getClientIdCountByGroup($type) > 0 ) {
        //         Gateway::sendToGroup($type,json_encode($data,JSON_UNESCAPED_UNICODE));
        //     }
        // });


        // echo "---------------------------------------\n";
        // echo "[WorkerStart]\n";
    }

    /**
    * 当客户端连接上gateway进程时(TCP三次握手完毕时)触发的回调函数
    * @param int $client_id 连接标识
    */
    public static function onConnect($client_id)
    {
        // echo "---------------------------------------\n";
        // echo "[ClientConnect]\n";
        // echo "client_id: $client_id\n";
    }

    /**
     * 当客户端连接上gateway完成websocket握手时触发的回调函数
     * 注意：此回调只有gateway为websocket协议并且gateway没有设置onWebSocketConnect时才有效
     * @param  [type] $client_id 连接标识
     * @param  [type] $data      websocket握手时的http头数据，包含get、server等变量
     */
    public static function onWebSocketConnect($client_id, $data)
    {
        $group = $data['get']['group'] ?? '';

        if ( empty($group) ) {
            Gateway::closeClient($client_id);
        } else {
            $arr = explode(',',$group);
            foreach ($arr as $key => $val) {
                Gateway::joinGroup($client_id, $val);
            }
        }
        

        // $token = $data['get']['token'] ?? '';
        // $group = $data['get']['group'] ?? '';

        // if ( Jwt::check($token) ) {
        //     $arr = explode(',',$group);
        //     foreach ($arr as $key => $val) {
        //         Gateway::joinGroup($client_id, $val);
        //     }
        // } else {
        //     Gateway::closeClient($client_id);
        // }


        // echo "---------------------------------------\n";
        // echo "[WebSocketConnect]\n";
        // echo "client_id: $client_id\n";
    }

    /**
    * 当客户端发来数据(Gateway进程收到数据)后触发的回调函数
    * @param int $client_id 连接标识
    * @param mixed $message 完整的客户端请求数据，数据类型取决于Gateway所使用协议的decode方法返的回值类型
    */
    public static function onMessage($client_id, $message)
    {
        // $msg = json_decode($message,true);

        // $uid = $_SESSION['uid'];
        // $arr = explode('_',$uid);
        // if ('console' == $arr[0]) {
        //     $type = $msg['type'];
        //     $data = $msg['data'] ?? [];
        //     switch ($type) {
        //         /*提交充值订单*/
        //         case 'submit_rech_order':
        //             Gateway::sendToUid($data['uid'],json_encode([
        //                 'type' => 'submit_rech_order',
        //                 'data' => [
        //                     'id'      => intval($data['id']),
        //                     'money'   => intval($data['money']),
        //                     'account' => $data['account'],
        //                     'time'    => date('Y-m-d H:i:s'),
        //                 ],
        //             ],JSON_UNESCAPED_UNICODE));
        //             break;
        //     }
        // }

        // if ('autobet' == $arr[0]) {
        //     $type = $msg['type'];
        //     $data = $msg['data'] ?? [];
        //     switch ($type) {
        //         case 'request_order':
        //             $usrA = 'autobet_A_' . $arr[2];
        //             $usrB = 'autobet_B_' . $arr[2];
        //             $nilA = Gateway::isUidOnline($usrA);
        //             $nilB = Gateway::isUidOnline($usrB);
        //             if ($nilA && $nilB) {
        //                 $order = Mssc::randomOrder($data['money']);
        //                 Gateway::sendToUid($usrA,json_encode(['type'=>'response_order','data'=>$order['A']],JSON_UNESCAPED_UNICODE));
        //                 Gateway::sendToUid($usrB,json_encode(['type'=>'response_order','data'=>$order['B']],JSON_UNESCAPED_UNICODE));
        //             }
        //             break;
        //     }
        // }


        // echo "---------------------------------------\n";
        // echo "[ClientMessage]\n";
        // echo "client_id: $client_id\n";
        // echo "message: $message\n";
    }

    /**
    * 客户端与Gateway进程的连接断开时触发
    * 不管是客户端主动断开还是服务端主动断开，都会触发这个回调，一般在这里做一些数据清理工作
    * 注意：
    * onClose回调里无法使用Gateway::getSession()来获得当前用户的session数据，但是仍然可以使用$_SESSION变量获得
    * onClose回调里无法使用Gateway::getUidByClientId()接口来获得uid，
    * 解决办法是在Gateway::bindUid()时记录一个$_SESSION['uid']，onClose的时候用$_SESSION['uid']来获得uid
    * @param int $client_id 连接标识
    */
    public static function onClose($client_id)
    {
        // $uid = $_SESSION['uid'];
        // $arr = explode('_',$uid);
        // if ('inject' == $arr[0]) {
        //     /*推送消息:站点离线*/
        //     // Gateway::sendToGroup('console',json_encode([
        //     //     'type' => 'website_outline',
        //     //     'data' => [
        //     //         'uid' => $uid,
        //     //     ],
        //     // ],JSON_UNESCAPED_UNICODE));

        //     /*推送消息:在线站点列表*/
        //     $list = (new WebSite)->online();
        //     foreach ($list as $k => $v) {
        //         $list[$k]['rechcfgs'] = (new RechCfgs)->get($v['uid']);
        //         $list[$k]['rechlist'] = (new RechList)->get($v['uid']);
        //         $list[$k]['drawlist'] = (new DrawList)->get($v['uid']);
        //     }
        //     Gateway::sendToGroup('console',json_encode([
        //         'type' => 'website_list',
        //         'data' => array_values($list),
        //     ],JSON_UNESCAPED_UNICODE));
        // }


        echo "---------------------------------------\n";
        echo "[ClientClose]\n";
        echo "client_id: $client_id\n";
    }

    /**
     * 当businessWorker进程退出时触发,每个进程生命周期内都只会触发一次
     * 可以在这里为每一个businessWorker进程做一些清理工作，例如保存一些重要数据等
     * 注意：某些情况将不会触发onWorkerStop，例如业务出现致命错误FatalError，或者进程被强行杀死等情况
     * @param  [type] $businessWorker [description]
     */
    public static function onWorkerStop($businessWorker)
    {
        echo "---------------------------------------\n";
        echo "[WorkerStop]\n";
    }

}