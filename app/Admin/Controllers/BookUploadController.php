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
use Artisan;


class BookUploadController extends BaseController
{ 


    public function index(Request $r){

        // 转码
        // $content = file_get_contents("/Users/wangxin/Desktop/jy.txt");

        // $encode = mb_detect_encoding($content, array("ASCII","UTF-8","GB2312","GBK","BIG5")); 
        // //cp936
        // $str_encode = mb_convert_encoding($content, 'UTF-8', $encode);


        // echo $str_encode;

        // file_put_contents('/Users/wangxin/Desktop/test.txt', $str_encode);

        //$content = file_get_contents("/Users/wangxin/Desktop/test.txt");

        // $pos1 = strpos($content,"\n");

        // //标题和作者 
        // $title_author =substr($content, 0, $pos1);

        // $title_author_arr  = explode(' ', $title_author);

        // //标题
        // $title = $title_author_arr[0];
        // //作者
        // $author = str_replace('作者：', '', $title_author_arr[1]);


        // //除第一行外所有内容
        // $second_content =substr($content,$pos1+1);


        /*

            content
        */

        if($r->isMethod('get')){
            return Admin::content(function (Content $content) {
                $content->body(view('admin/book_upload/index'));
            }); 
        }else{

           // $book_name = $this->uploadFiles($r['files'],'test_tmp/');
            $r['files']->move('upload_book/',  $r['files']->getClientOriginalName());

           // $exitCode = Artisan::call('BooksUpload '.$book_name['oldname']);
            


            //上传成功跳转到书籍页面

            return redirect('/admin/book/bookIndex');

       }
   }

    public function updateBook(Request $r){

        if($r->isMethod('get')){
            return Admin::content(function (Content $content) {
                $content->body(view('admin/book_upload/update_index'));
            }); 
        }else{

           // $book_name = $this->uploadFiles($r['files'],'test_tmp/');
            $r['files']->move('export_book/',  $r['files']->getClientOriginalName());

           // $exitCode = Artisan::call('BooksUpload '.$book_name['oldname']);
            $isHave = (new AdminModel('update_book_log'))->getOne([
                'title' => $r['files']->getClientOriginalName(),
                'status' => 0,
            ]);
            if(empty($isHave)) {
                (new AdminModel('update_book_log'))->addArray([
                    'title' => $r['files']->getClientOriginalName(),
                    'created_at' => date( "Y-m-d H:i:s", time()),
                    'status' => 0,
                ]);
            }
            


            //上传成功跳转到书籍页面

            return redirect('/admin/book/bookIndex');

       }

       


       // $exitCode = Artisan::call('BooksUpload 将夜');
        
        // if($r->isMethod('get')){
        //     return Admin::content(function (Content $content) {
        //         $content->body(view('admin/book_upload/index'));
        //     }); 
        // }else{

        //     $this->uploadFiles($r['files'],'txt');
        // }

        //echo 1;
    }
    
    


}
