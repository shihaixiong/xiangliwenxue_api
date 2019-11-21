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
 * 推荐位
 *
 */

class RecommendController extends BaseController
{ 

    //推荐位列表
    public function recommendIndex(Content $content,Request $r){
        return $content->body(view('admin/recommend/recommend_index'));
    }
    public function recommendGetIndex(Content $content,Request $r){

        $data['data']=(new AdminModel('recommend_type'))->getAll(['position'=>1]);

        echo json_encode($data);

    }

    //推荐位新增
    public function createRecommend(Content $content,Request $r){

       return $content->body(view('admin/recommend/recommend_add'));
        
    }
    public function addRecommend(Content $content,Request $r){

        
        $data['name']    = $r->name;
        // $data['count']   = $r->count;
        $data['type']    = $r->type;
        $data['channel'] = $r->channel ?? 1;
        $data['style']   = $r->style;
        $data['vip'] = $r->vip;
        $data['count']   = 0;
        switch ($data['style']) {
            case 1:
               $data['count'] = 8;
                break;
            case 2:
               $data['count'] = 3;
                break;
            case 3:
               $data['count'] = 4;
                break;
            case 4:
               $data['count'] = 4;
                break;
            case 5:
               $data['count'] = 1;
                break;
            case 6:
               $data['count'] = 10;
                break;
        }

        $data['created_at'] = date('Y-m-d h:i:s',time());
        $data['updated_at'] = date('Y-m-d h:i:s',time());

        $count=(new AdminModel('recommend_type'))->getCount(['channel'=>$r->channel,'position'=>1]);

        $data['displayorder'] = $count+1;



        $rec_id=(new AdminModel('recommend_type'))->addGetId($data);

        // if(!$rec_id) return false;

        // for($i=0; $i<$data['count']; $i++){

        //     (new AdminModel('book_recommend'))->addArray([
        //         'rec_id'       => $rec_id,
        //         'displayorder' => $i+1,
        //         'created_at'   => date('Y-m-d h:i:s',time()),
        //         'updated_at'   => date('Y-m-d h:i:s',time()),
        //     ]);
        // }

        return $content->body(view('admin/recommend/recommend_index'));
           
    }

    //推荐位编辑
    public function editRecommend(Content $content,Request $r){
        $rec_id = $r->rec_id;

        $arr = (new AdminModel('recommend_type'))->getOne(['id'=>$rec_id]);

        return $content->body(view('admin/recommend/recommend_edit')->with('data',$arr));
  
    }
    public function updateRecommend_(Content $content,Request $r){

        $data['name']       = $r->name;
        // $data['count']      = $r->count;
        $data['type']       = $r->type;
        $data['channel']    = $r->channel ?? 1;
        $data['style']      = $r->style;
        $data['updated_at'] = date('Y-m-d h:i:s',time());
        $data['vip'] = $r->vip;
        $id         = $r->id;
        $data['count']   = 0;
        switch ($data['style']) {
            case 1:
               $data['count'] = 8;
                break;
            case 2:
               $data['count'] = 3;
                break;
            case 3:
               $data['count'] = 4;
                break;
            case 4:
               $data['count'] = 4;
                break;
            case 5:
               $data['count'] = 1;
                break;
            case 6:
               $data['count'] = 10;
                break;
        }

        $displayorder= $r->displayorder;
        $displayorder_old= $r->displayorder_old;

        if($displayorder != $displayorder_old){
            $oldrid=(new AdminModel('recommend_type'))->getOne(['displayorder'=>$displayorder,'channel'=>$r->channel]);
            if($oldrid){
                (new AdminModel('recommend_type'))->editArray(['id'=>$oldrid['id']],['displayorder'=>$displayorder_old,'updated_at'=>$data['updated_at']]);
            }
           
        }
        
        $data['displayorder']= $displayorder;


        // $arr = (new AdminModel('recommend_type'))->getOne(['id'=>$data['id']]);


        // if($arr['count']<$data['count']){

        //     $count_ = $data['count'] - $arr['count'];

        //     for($i=0; $i<$count_; $i++){
        //         (new AdminModel('book_recommend'))->addArray([
        //             'rec_id'       => $data['id'],
        //             'displayorder' => $arr['count']+$i+1,
        //             'created_at'   => date('Y-m-d h:i:s',time()),
        //             'updated_at'   => date('Y-m-d h:i:s',time()),
        //         ]);
        //     }
        // }

        (new AdminModel('recommend_type'))->editArray(['id'=>$id],$data);

        return $content->body(view('admin/recommend/recommend_index')); 
           
    }

    //推荐位删除
    public function delRecommend(Content $content,Request $r){

        $rec_id = $r->rec_id;
        (new AdminModel('recommend_type'))->delArray(['id'=>$rec_id]);
        
        return $content->body(view('admin/recommend/recommend_index'));

    }



    //推荐位详情内容列表
    public function recommendInfoIndex(Content $content,Request $r){

        return $content->body(view('admin/recommend/recommend_info_index'));
      
    }
    public function recommendInfoGetIndex(Content $content,Request $r){

        $rec_id=$r->rec_id;
        $res=(new AdminModel('book_recommend'))->getAll(['rec_id'=>$rec_id]);
        foreach ($res as $key => $value) {
            if(!empty($value['articleid'])) {
                $bookInfo = (new AdminModel('books'))->getOne(['id'=>$value['articleid']]);
                $res[$key]['title'] = $bookInfo['title'];
                $res[$key]['image'] = $bookInfo['image'];
            } else {
                $res[$key]['title'] = '';
            }
        }
        echo json_encode(['data'=>$res]);
      
    }

    //推荐位详情内容新增
    public function createRecommendInfo(Content $content,Request $r){

        $rec_id = $r->rec_id;
       
        $type =(new AdminModel('recommend_type'))->getOne(['id'=>$rec_id],'type');
        return $content->body(view('admin/recommend/recommend_info_add')->with('rec_id',$rec_id)->with('type',$type['type']));
        
    }
    public function addRecommendInfo(Content $content,Request $r){

        if($r->articleid){
            $data['articleid'] = $r->articleid;
        }else{
            $data[$r->select_type] = $r->values;
        }
        
        $data['rec_id']  = $r->rec_id;
        $data['type']    = $r->type;

        if($r['pic']){
            $image = $this->uploadFiles($r['pic'],'upload/recommend_pic/');
            $data['image']=$image['newname'];
        }
        
        
        $count=(new AdminModel('book_recommend'))->getCount(['rec_id'=>$data['rec_id']]);

        $data['displayorder'] = $count+1;

        $data['created_at'] = date('Y-m-d h:i:s',time());
        $data['updated_at'] = date('Y-m-d h:i:s',time());

        
        (new AdminModel('book_recommend'))->addArray($data);

        
        return redirect('/admin/recommend/recommendInfoIndex?rec_id='.$data['rec_id']);
           
    }

    //推荐位编辑
    public function editRecommendInfo(Content $content,Request $r){

        $rec_id = $r->rec_id;

        $arr = (new AdminModel('recommend_type'))->getOne(['id'=>$rec_id]);


        $id = $r->id;

        $arr2= (new AdminModel('book_recommend'))->getOne(['id'=>$id]);

        
        return $content->body(view('admin/recommend/recommend_info_edit')->with('data',$arr)->with('data2',$arr2));
  
    }
    public function updateRecommendInfo(Content $content,Request $r){

        if($r->articleid){
            $data['articleid'] = $r->articleid;
        }else{
            $data[$r->select_type] = $r->values;
        }
        
        $data['rec_id']  = $r->rec_id;
        $data['type']    = $r->type;
        $data['id']      = $r->id;

        if($r['pic']){
             $image = $this->uploadFiles($r['pic'],'upload/recommend_pic/');
             $data['image']=$image['newname'];
        }
        

        $data['updated_at'] = date('Y-m-d h:i:s',time());
        
        $displayorder= $r->displayorder;
        $displayorder_old= $r->displayorder_old;

        if($displayorder != $displayorder_old){
            $oldrid=(new AdminModel('book_recommend'))->getOne(['rec_id'=>$data['rec_id'],'displayorder'=>$displayorder]);
            if($oldrid){
                (new AdminModel('book_recommend'))->editArray(['id'=>$oldrid['id']],['displayorder'=>$displayorder_old,'updated_at'=>$data['updated_at']]);
            }
           
        }
        
        $data['displayorder']= $displayorder;
        (new AdminModel('book_recommend'))->editArray(['id'=>$data['id']],$data);

        
        return redirect('/admin/recommend/recommendInfoIndex?rec_id='.$data['rec_id']);
           
    }


    //推荐位详情内容删除
    public function delRecommendInfo(Content $content,Request $r){

        $id = $r->id;
        $rec_id = $r->rec_id;
        (new AdminModel('book_recommend'))->delArray(['id'=>$id]);
        
        return redirect('/admin/recommend/recommendInfoIndex?rec_id='.$rec_id);

    }

     //推荐位添加书籍时 查看书籍id 是否存在
    public function isBook(Content $content,Request $r){

        $id = $r->id;
        
        $res=(new AdminModel('books'))->getOne(['id'=>$id]);

        if($res) return 1;
        return 0;

    }


    public function shelfRec(Content $content, Request $r) {
        $recid = 99999;
        $res=(new AdminModel('book_recommend'))->getAll(['rec_id'=>$recid]);
        $rec = (new AdminModel('recommend_type'))->getOne(['id'=>$recid]);
        $recid = 100000;
        $vip=(new AdminModel('book_recommend'))->getAll(['rec_id'=>$recid]);
        $vipRec = (new AdminModel('recommend_type'))->getOne(['id'=>$recid]);

        $recData = [
            ['name'=>$rec['name']],['name'=>$vipRec['name']]
        ];

        return $content->body(view('admin/recommend/shelf_rec',['data'=>$res,'vipData'=>$vip,'rec'=>$recData]));

    }

    public function updShelfRec(Request $r) {
        $recid = $r->input('rid');
        $articleid = $r->input('articleid');
        (new AdminModel('book_recommend'))->delArray(['rec_id'=>$recid]);
        $vipRec = (new AdminModel('recommend_type'))->editArray(['id'=>$recid],['name'=>$r->input('name')]);

        foreach ($articleid as $key => $value) {
            $data = [
                'articleid' => $value,
                'rec_id' => $recid,
                'displayorder' => $key+1,
                'created_at' =>date( "Y-m-d H:i:s", time()),
                'updated_at'=> date( "Y-m-d H:i:s", time()),
                'type' => 1,
            ];
            $res = (new AdminModel('book_recommend'))->addArray($data);
        }
        return redirect('/admin/recommend/shelfRec');

    }
}
