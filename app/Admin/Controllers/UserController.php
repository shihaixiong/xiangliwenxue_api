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
 * 用户管理
 *
 */

class UserController extends BaseController
{ 

    //书籍列表
    public function userIndex(Content $content,Request $r){
        $user = (new AdminModel('user'))->getDb()->paginate(20);

        return $content->body(view('admin/user/user_index',['data'=>$user]));
    }
    public function userGetIndex(Content $content,Request $r){

        $data['data']=(new AdminModel('user'))->getAll();

        echo json_encode($data);


    }

    public function upd(Request $r,Content $content) {
        $userid = $r->id;
        $data = (new AdminModel('user'))->getOne(['id'=>$userid]);
        return $content->body(view('admin/user/upd',['data'=>$data]));
    }

    public function updDo(Request $r) {
        $id = $r->id;
        $remain = $r->remain;
        $money = $r->money;
        $integral = $r->integral;
        $data = [
            'remain' => $r->remain,
            'money' => $r->money,
            'integral' => $r->integral
        ];
        $res = (new AdminModel('user'))->editArray(['id'=>$id],$data);
        return redirect('/admin/user/userIndex');

    }
}