<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Services\FuncService;
use App\Services\TaskService;
use Cache;

/**
 * @group 任务中心
 */
class TaskController extends BaseController
{
    protected $task;
    protected $func;
    public function __construct(TaskService $task, FuncService $fun) {
        $this->task = $task;
        $this->func = $fun;
    }
     /**
     * 获取任务中心列表 api/task/list
     * 成功返回1 失败返回0+msg
     * @response {"code":1,"msg":"success","data":{"result":[{"id":1,"task_name":"\u6bcf\u65e5\u7b7e\u5230","type":1,"reward_integral":10,"status":2},{"id":2,"task_name":"\u6bcf\u65e5\u767b\u5f55","type":1,"reward_integral":1,"status":2},{"id":3,"task_name":"\u6bcf\u65e5\u5206\u4eab","type":1,"reward_integral":20,"status":0},{"id":4,"task_name":"\u8ba2\u9605\u7ae0\u8282","type":2,"reward_integral":5,"status":0},{"id":5,"task_name":"\u5145\u503c","type":2,"reward_integral":100,"status":0}]}}
     * @response {"code":0,"msg":"暂无任务","data":{}}
     */
    public function getTaskList(Request $r) {
        $userid = getUserId($r->header('token'));
        $this->task->addTaskLog($userid,2);
        $list = $this->task->getTaskList($userid);
        apiReturn(1,'success', ['result'=>$list]);
       
    }

     /**
     * 完成任务 api/task/finish
     * 成功返回1 失败返回0+msg
     * @bodyParam tid int required 任务id
     * @response {"code":1,"msg":"success","data":{}}
     * @response {"code":0,"msg":"网络错误,请重试","data":{}}
     */
    public function finishTask(Request $r){
        $userid = getUserId($r->header('token'));
        $tid = $r->input('tid');
        $res = $this->task->finishTask($userid, $tid);
        if($res) apiReturn(1, '领取成功');
        apiReturn(0,'网络错误,请重试');
    }
    
}
