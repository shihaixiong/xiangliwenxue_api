<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use Encore\Admin\Controllers\ModelForm;
use Illuminate\Support\Facades\DB;  
use App\Models\AdminModel;
use Illuminate\Http\Request;
use App\Services\UserService;

/**
 * 积分商城
 *
 */
class WithdrawController extends BaseController
{ 
    protected $user;
    public function __construct(UserService $user) {
        $this->user = $user;
    }
    public function withdrawIndex(Content $content,Request $r){
        return $content->body(view('admin/withdraw/withdraw_index'));
    }
    public function withdrawGetIndex(Content $content,Request $r){
        $data['data'] = (new AdminModel('withdrawal'))->getAll();
        
        echo json_encode($data);
    }
    public function updateStatus(Content $content,Request $r){
    	$id = $r->id;
        $res = (new AdminModel('withdrawal'))->editArray(['id'=>$id],['status'=>1,'updated_at'=>date('Y-m-d H:i:s',time())]);
        if($res){
        	echo 1;exit();
        }else{
        	echo 0;exit();
        }
    }

    public function updateStatusNo(Content $content,Request $r){
        $id = $r->id;
        $withdraw = (new AdminModel('withdrawal'))->getOne(['id'=>$id]);
        if($withdraw['status'] != 0) exit(0);
        $res = (new AdminModel('withdrawal'))->editArray(['id'=>$id],['status'=>2,'updated_at'=>date('Y-m-d H:i:s',time())]);
        //把余额加回去
        $userInfo = $this->user->getUserInfo(['id'=>$withdraw['userid']]);
        (new AdminModel('user'))->editArray(['id'=>$withdraw['userid']],['money'=>$userInfo['money']+$withdraw['money']]);
        if($res){
            echo 1;exit();
        }else{
            echo 0;exit();
        }
    }
}