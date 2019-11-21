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


/**
 * 积分商城
 *
 */
class ConfigController extends BaseController
{ 

    public function index(Content $content,Request $r){
        $return = (new AdminModel('config'))->getOne();
        $return['pay_info'] = json_decode($return['pay_info'],true);

        $task = (new AdminModel('task'))->getAll();
        return $content->body(view('admin/config/index',['data'=>$return,'task'=>$task]));
    }
    
    public function update(Content $content,Request $r) {
        $data = $r->input();
        $pay_info = $data['pay_info'];
        foreach ($pay_info as $key => $value) {
            if(empty($value['money'])) unset($pay_info[$key]);
        }
        $upd['pay_info'] = json_encode($pay_info);
        $upd['sign_coin'] = $data['sign_coin'];
        $upd['one_ratio'] = $data['one_ratio'];
        $upd['two_ratio'] = $data['two_ratio'];
        $upd['money_to_remain'] = $data['money_to_remain'];
        $upd['money_to_integral'] = $data['money_to_integral'];
        $upd['integral_to_remain'] = $data['integral_to_remain'];
        $return = (new AdminModel('config'))->editArray(['id'=>1],$upd);

        $task = $data['task'];
        foreach ($task as $key => $value) {
            $res = (new AdminModel('task'))->editArray(['id'=>$key],['reward_integral'=>$value]);
        }
        return redirect('/admin/config/index');
    }

    public function getPay($start = 0, $end = 0) {
        $where = [];
        if(!empty($start)) $where[] = ['created_at','>',$start];
        if(!empty($end)) $where[] = ['created_at','<',$end];
        $res = (new AdminModel('order'))->getAll($where);
        $data['num'] = count($res);
        $data['money'] = 0;
        $data['fin'] = 0;
        foreach ($res as $key => $value) {
            if($value['status'] == 1) {
                $data['money'] += $value['money'];
                $data['fin'] += 1;
            }
        }
        return $data;
    }

    public function getTodayPay() {
        $today = date( "Y-m-d", time());
        $data = $this->getPay($today);
        apiReturn(1,'success',$data);
    }

    public function getUser(Content $content,Request $r) {
        $return = (new AdminModel('expend'))->getDb()->select([DB::raw("sum(price) as price"),'articleid','userid'])->where('type',2)->orderBy('price','desc')->groupBy('userid')->paginate(20);
        foreach ($return as $key => $value) {
            $userInfo = (new AdminModel('user'))->getOne(['id'=>$value->userid]);
            if(empty($userInfo)) continue;
            $return[$key]->username = $userInfo['username'];
        }
        return $content->body(view('admin/statistics/user',['data'=>$return])); 
    }

    public function userInfo(Content $content,Request $r) {
        $return = (new AdminModel('expend'))->getDb()->where(['type'=>2,'userid'=>$r->input('id')])->orderBy('created_at','desc')->paginate(20);
        foreach ($return as $key => $value) {
            $bookInfo = (new AdminModel('books'))->getOne(['id'=>$value->articleid]);
            if(empty($bookInfo)) continue;
            $return[$key]->title = $bookInfo['title'];
        }
        return $content->body(view('admin/statistics/userInfo',['data'=>$return]));        

    }

    public function bookExpend(Content $content,Request $r) {
        $return = (new AdminModel('expend'))->getDb()->select([DB::raw("sum(price) as price"),'articleid'])->where('type',2)->orderBy('price','desc')->groupBy('articleid')->paginate(20);
        foreach ($return as $key => $value) {
            $bookInfo = (new AdminModel('books'))->getOne(['id'=>$value->articleid]);
            if(empty($bookInfo)) continue;
            $return[$key]->title = $bookInfo['title'];
        }
        return $content->body(view('admin/statistics/book',['data'=>$return]));        

    }

    public function bookInfo(Content $content,Request $r) {
        $return = (new AdminModel('expend'))->getDb()->where(['type'=>2,'articleid'=>$r->input('id')])->orderBy('created_at','desc')->paginate(20);
        $bookInfo = (new AdminModel('books'))->getOne(['id'=>$r->id]);
        foreach ($return as $key => $value) {
            if(empty($bookInfo)) continue;
            $return[$key]->title = $bookInfo['title'];
        }
        return $content->body(view('admin/statistics/info',['data'=>$return]));        

    }
}