<?php

namespace App\Services;

use Cache;
use App\Models\CommonModel;
use DB;
use App\Services\TaskService;
/**
 * Class EmailService
 *
 * @package \App\Services
 */
/**
 * Class EmailService
 *
 * @package App\Services
 */
class PayService
{
    protected $task;

    // public function __construct(TaskService $task) {
    //     $this->task = $task;
    // }
    public function expend($userid, $price, $subject, $type = 2, $chapterid = 0, $articleid = 0) {
        //减余额
        $updRemain = $this->updRemain($userid, $price, $type);

        //添加消费记录
        $data = [
            'userid'     => $userid,
            'price'      => $price,
            'type'       => $type,
            'subject'    => $subject,
            'chapterid'  => $chapterid,
            'articleid'  => $articleid,
            'created_at' => date('Y-m-d H:i:s', time()),
        ];
        $addexpend = $this->addExpendLog($data);

        if($updRemain && $addexpend) {
            return true;
        }else {
            return false;
        }
    }

    public function updRemain($userid, $price, $type) {
        if($type == 1) {
            $res = (new CommonModel('user'))->getDb()->where('id',$userid)->increment('remain', $price);
            if($res) return true;
        }else {
            $res = (new CommonModel('user'))->getDb()->where('id',$userid)->decrement('remain', $price);
            if($res) return true;
        }
        return false;
    }

    public function addExpendLog($data) {
        $res = (new CommonModel('expend'))->addArray($data);
        if($res) return true;
        return false;
    }


    public function updIntegral($userid, $num, $type) {
        if($type == 1) {
            $res = (new CommonModel('user'))->getDb()->where('id',$userid)->increment('integral', $num);
            if($res) return true;
        }else {
            $res = (new CommonModel('user'))->getDb()->where('id',$userid)->decrement('integral', $num);
            if($res) return true;
        }
        return false;
    }

    public function makeOrder($userid, $money, $articleid = 0, $coin ,$purchase_token = '') {
        if(!empty($purchase_token)) {
            $res = (new CommonModel('order'))->getOne(['purchase_token'=>$purchase_token]);
            if($res) return false;
        }
        $orderSn = "SN_".time().$userid.rand(100000,999999);
        $data = [
            'userid' => $userid,
            'money' => $money,
            'coin'  => $coin,
            'from_articleid' => $articleid,
            'created_at' => date( "Y-m-d H:i:s", time()),
            'order_sn' => $orderSn,
            'purchase_token' => $purchase_token
        ];
        $res = (new CommonModel('order'))->addArray($data);
        if($res) return $orderSn;
        return false;
    }


    public function callBack($orderSn, $money) {
        $this->task = new TaskService($this);
        $order = (new CommonModel('order'))->getOne(['order_sn'=>$orderSn]);
        $userid = $order['userid'];
        if($order['status'] == 1) return false;
        //修改订单状态
        $res = (new CommonModel('order'))->editArray(['order_sn'=>$orderSn],['status'=>1,'finish_at'=>date( "Y-m-d H:i:s", time())]);
        //给用户加钱
        //添加充值记录
        $res = $this->expend($userid, $order['coin'], '充值', 1, 0, 0);
        //检测是否用分销
        $res = (new CommonModel('user_relation'))->getOne(['userid'=>$userid]);
        if($res) {
            //分销奖励比例
            $config = (new CommonModel('config'))->getOne();
            $remain = $order['money']*$config['one_ratio']/100;
            $tmpUserid = $res['parentid'];
            $res = (new CommonModel('user'))->getDb()->where('id',$res['parentid'])->increment('money',$remain);
            if($res) {
                (new CommonModel('money_log'))->addArray(
                    [
                        'userid'=> $tmpUserid,
                        'from_userid' => $order['userid'],
                        'money' => $remain,
                        'created_at' => date( "Y-m-d H:i:s", time()),
                        'order_sn' => $order['order_sn'],
                        'level' => 1,
                    ]
                );
            }
            //检测是否还有上一级
            $parent = (new CommonModel('user_relation'))->getOne(['userid'=>$tmpUserid]);
            if($parent){
                $oUserid = $tmpUserid;
                $remain = $order['money']*$config['two_ratio']/100;
                $res = (new CommonModel('user'))->getDb()->where('id',$parent['parentid'])->increment('money',$remain);
                if($res) {
                    (new CommonModel('money_log'))->addArray(
                        [
                            'userid'=> $parent['parentid'],
                            'from_userid' => $oUserid,
                            'money' => $remain,
                            'created_at' => date( "Y-m-d H:i:s", time()),
                            'order_sn' => $order['order_sn'],
                            'level' => 2,
                        ]
                    );
                }
            }
        }
        //用户变成vip用户
        $res = (new CommonModel('user'))->editArray(['id'=>$userid],['is_vip'=>1]);
        //完成充值任务
        $this->task->addTaskLog($userid, 5);
        return true;
    }

    public function getPayConfig($id) {
        $config = (new CommonModel('config'))->getOne();
        $payInfo = json_decode($config['pay_info'],true);
        foreach ($payInfo as $key => $value) {
            if($value['id'] == $id) {
                return $value;
            }
        }
        return false;
    }

    public function getOrder($orderSn) {
        $res = (new CommonModel('order'))->getOne(['order_sn'=>$orderSn]);
        if($res) return $res;
        return false;
    }
}
