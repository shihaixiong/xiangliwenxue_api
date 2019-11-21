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
use App\Services\FuncService;

/**
 * 积分商城
 *
 */
class IntegralOrderController extends BaseController
{ 
    protected $func;
    public function __construct(FuncService $func) {
        $this->func = $func;
    }
    // 订单列表
    public function orderIndex(Content $content,Request $r){
        return $content->body(view('admin/integral_order/order_index'));
    }
    public function orderGetIndex(Content $content,Request $r){
        $data['data'] = (new AdminModel('integral_order'))->getAll();
        
        echo json_encode($data);
    }

    //修改物流信息
    public function updateExpress(Content $content,Request $r){
        $express = $r->express;
        $id = $r->id;
        $res = (new AdminModel('integral_order'))->editArray(['id'=>$id],['express_num'=>$express,'updated_at'=>date('Y-m-d h:i:s',time())]);

        if($res){
            $order = (new AdminModel('integral_order'))->getOne(['id'=>$id]);
            $res = $this->func->sendOrderMsg($express, 1, $order['tel'],$order['goods_name']);
            echo 1;
        }else{
            echo 2;
        }

    }

}