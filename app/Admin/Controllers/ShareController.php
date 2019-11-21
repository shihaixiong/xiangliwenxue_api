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
 * 推广管理
 *
 */
class ShareController extends BaseController
{ 

    //推广列表
    public function index(Content $content,Request $r){
        $book_name = $r->input('book_name');
        $data = (new AdminModel('spread'))->getDb();
        if(!empty($book_name)) {
            $data->where('book_name',$book_name);
        }

        $data = $data->orderBy('id','desc')->paginate(20);
        return $content->body(view('admin/share/index',['data'=>$data,'webUrl'=> env('WEB_URL')]));
    }

    public function add(Content $content, Request $r) {
        return $content->body(view('admin/share/add'));

    }

    public function addspread( Request $r) {
        $file = $this->uploadFiles($r['pic'],'spread/');
        $images = [];
        if(!empty($r->images)) {
            foreach($r->images as $v) {
                $img = $this->uploadFiles($v,'spread/');
                $images[] = $img['newname'];
            }
        }
        $data = [
            'articleid' => $r->articleid,
            'book_name' => $r->check_title,
            'show_num'  => $r->show_num,
            'start_num' => $r->start_num,
            'short_rec' => $r->short_rec,
            'style'     => $r->style,
            'start_view_num' => $r->start_view_num,
            'title'     => $r->title,
            'add_time'  => time(),
            'first_img' => $file['newname'] ?? '',
            'images'    => json_encode($images),
            'language'  => $r->language,
        ];
        $res = (new AdminModel('spread'))->addArray($data);
        return redirect('/admin/share/index');
    }
    
    public function count(Content $content,Request $r) {
        $data = (new AdminModel('spread_count'))->getAll(['sid'=>$r->id]);
        return $content->body(view('admin/share/count',['data'=>$data]));

    }

    public function del(Request $r) {
        $data = (new AdminModel('spread'))->delArray(['id'=>$r->id]);
        return  redirect('/admin/share/index');
    }

    public function upd(Content $content,Request $r) {
        $id = $r->input('id');
        $data = (new AdminModel('spread'))->getOne(['id'=>$r->id]);
        $data['first_img'] = imageUrl($data['first_img']);
        $image = json_decode($data['images'], true);
        $dasta['images'] = [];
        foreach ($image as $key => $value) {
            $data['oimages'][] = imageUrl($value);
        }
        return $content->body(view('admin/share/upd',['data'=>$data]));

    }

    public function update(Request $r) {
        $id = $r->id;
        if($r['pic']){
            $file = $this->uploadFiles($r['pic'],'spread/');
        }
        $spread = (new AdminModel('spread'))->getOne(['id'=>$id]);
        $imgs = json_decode($spread['images'], true);
        if(!empty($r->images)) {
            foreach($r->images as $k => $v) {
                $img = $this->uploadFiles($v,'spread/');
                $imgs[$k] = $img['newname'];
            }
        }
        $data = [
            'articleid' => $r->articleid,
            'book_name' => $r->check_title,
            'show_num'  => $r->show_num,
            'start_num' => $r->start_num,
            'short_rec' => $r->short_rec,
            'style'     => $r->style,
            'start_view_num' => $r->start_view_num,
            'title'     => $r->title,
            'language'  => $r->language,
        ];
        if(!empty($file['newname'])) {
            $data['first_img'] = $file['newname'];
        }
        if(!empty($imgs)) {
            $data['images'] = json_encode($imgs);
        }
        $res = (new AdminModel('spread'))->editArray(['id'=>$id],$data);
        return redirect('/admin/share/index');
    }
}