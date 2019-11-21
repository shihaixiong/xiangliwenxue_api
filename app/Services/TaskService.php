<?php

namespace App\Services;

use Cache;
use DB;
use App\Models\CommonModel;
use App\Services\PayService;
/**
 * Class EmailService
 *
 * @package \App\Services
 */
/**
 * 任务中心 
 *
 * @package App\Services
 */
class TaskService
{
    public $pay;
    public function __construct(PayService $pay) {
        $this->pay = $pay;
    }
    public function addTaskLog($userid, $tid) {
        //查询任务详情
        $task = (new CommonModel('task'))->getOne(['id'=>$tid]);
        if(empty($task)) return false;
        //检测任务类型 如果是1 检测当日是否已经添加过了  如果是2 无限添加
        if($task['type'] == 1) {
            $where = [ 'tid' => $tid, 'userid' => $userid, 'date' => date( "Ymd", time())];
            $isSet = (new CommonModel('task_log'))->getOne($where);
            if($isSet) return false;
        }
        $insert = [
            'tid'             => $tid,
            'userid'          => $userid,
            'reward_integral' => $task['reward_integral'],
            'created_at'      => date( "Y-m-d H:i:s", time()),
            'date'            => date( "Ymd", time()),
            'status'          => 0,
            'type'            => $task['type'],
        ];
        $res = (new CommonModel('task_log'))->addArray($insert);
        if($res) return true;
        return false;
    }

    public function getTaskList($userid) {
        $task = (new CommonModel('task'))->getAll();
        foreach ($task as $key => $value) {
            if($value['type'] == 1) {
                $task[$key]['status'] = 0;
                $ishave = (new CommonModel('task_log'))->getOne(['userid'=>$userid,'tid'=>$value['id'],'date'=>date( "Ymd", time())]);
                if(isset($ishave) && $ishave['status'] == 0) $task[$key]['status'] = 1;
                if(isset($ishave) && $ishave['status'] == 1)$task[$key]['status'] = 2;
            }else{
                $task[$key]['status'] = 0;
                $ishave = (new CommonModel('task_log'))->getOne(['userid'=>$userid,'tid'=>$value['id'],'status' => 0]);
                $where = ['userid'=>$userid,'tid'=>$value['id'],'status'=>0];
                $userLog = (new CommonModel('task_log'))->getAll($where);
                if(!empty($userLog)) {
                    $integral = 0;
                    foreach ($userLog as $value) {
                        $integral += $value['reward_integral'];
                    }
                    $task[$key]['reward_integral'] = $integral;
                } 
                if(isset($ishave) && $ishave['status'] == 0) $task[$key]['status'] = 1;
            }
        }
        return $task;
    }

    public function finishTask($userid, $tid) {
         //开启事务
        DB::beginTransaction(); 
        //查询是否有任务
        $task = (new CommonModel('task'))->getOne(['id' => $tid]);
        if($task['type'] == 1) {
            $where = ['userid'=>$userid,'tid'=>$tid,'date'=>date( "Ymd", time()),'status'=>0];
            $ishave = (new CommonModel('task_log'))->getOne($where);
            if(empty($ishave)) return false;
            $integral = $ishave['reward_integral'];
        }else{
            $where = ['userid'=>$userid,'tid'=>$tid,'status'=>0];
            $ishave = (new CommonModel('task_log'))->getAll($where);
            if(empty($ishave)) return false;
            $integral = 0;
            foreach ($ishave as $key => $value) {
                $integral += $value['reward_integral'];
            }
        }
        //完成任务 
        $finishTask = (new CommonModel('task_log'))->editArray($where, ['status' => 1]);
        //加积分
        if($finishTask) {
            $updInt = $this->pay->updIntegral($userid, $integral, 1);
            //增加积分记录
            (new CommonModel('integral_log'))->addArray([
                'userid' => $userid,
                'num'    => $integral,
                'type'   => 2,
                'created_at' => date( "Y-m-d H:i:s", time()),
                'desc'   => "完成".$task['task_name'].'任务',
            ]);
        }
        
        //成功提交  失败回滚
        if($finishTask && $updInt) {
            DB::commit();
            return true;
        } else {
            DB::rollBack();
            return false;
        }
    }
}
