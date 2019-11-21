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
class StatisticsController extends BaseController
{ 

    public function statisticsIndex(Content $content,Request $r){
        $month = $r->input('month') ? date("Ym",strtotime($r->input('month'))) : date("Ym");
        $return = [];
        $return = (new AdminModel('statistics'))->getAll(['month' => $month]);

        return $content->body(view('admin/statistics/statistics_index',['data'=>$return]));
    }
    
    public function pay(Content $content,Request $r) {
        $return = [];
        //今日充值
        $today = date( "Y-m-d", time());
        $data['today'] = $this->getPay($today);
        //昨日充值
        $yesterday = date( "Y-m-d", time()-86400);
        $data['yesterday'] = $this->getPay($yesterday,$today);
        //当月充值
        $month = date( "Y-m-01", time());
        $data['month'] = $this->getPay($month,$today);
        //累计充值
        $month = date( "Y-m-01", time());
        $data['all'] = $this->getPay($month,$today);
        return $content->body(view('admin/statistics/pay',['data'=>$data]));
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