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



class DownController extends BaseController
{
    public function getDownList(Request $r,Content $content) {
        $data = (new AdminModel('down_config'))->getDb()->orderBy('id','desc')->paginate(20);
        
        return $content->body(view('admin/down/list',['data'=>$data]));
    }

    public function add(Request $r,Content $content) {
        return $content->body(view('admin/down/add'));
    }

    public function addDown(Request $r) {
        $newName = 'xiangliBookAndroid_'.$r->version.".apk";
        $file = $r['apk']->move('apk/',  $newName);
        $data = [
            'version'=> $r->version ?? '',
            'title' => $r->title ?? '',
            'note' => $r->note ?? '',
            'file' => $newName,
            'created_at' => date( "Y-m-d H:i:s", time()),
            'type' => $r->type
        ];
        $res = (new AdminModel('down_config'))->addArray($data);
        return redirect('/admin/down/list');

    }
    

    public function del(Request $r) {
        $id = $r->input('id');
        $res = (new AdminModel('down_config'))->delArray(['id'=>$id]);
        return redirect('/admin/down/list');
    }
} 
