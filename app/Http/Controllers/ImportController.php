<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use Encore\Admin\Controllers\ModelForm;
use Illuminate\Support\Facades\DB;  
use App\Models\AdminModel;
use Illuminate\Http\Request;


/**
 * 导入书籍
 *
 */
class ImportController extends BaseController
{ 

    public function addBook(Request $r){
        error_reporting(0);
        $data = $r->input();
        if(empty($data['desc']))      return apireturn(0, 'desc is null');
        if(empty($data['title']))     return apireturn(0, 'title is null');
        if(empty($data['image']))     return apireturn(0, 'image is null');
        if(empty($data['author']))    return apireturn(0, 'author is null');
        if(empty($data['sortid']))    return apireturn(0, 'sortid is null');
        $res  = (new AdminModel('import_book'))->getOne(['articleid'=>$data['articleid']]);
        if($res) apiReturn(0,'articleid repeat');
        try {
            $image = file_get_contents($data['image']);
            $name =  time().rand(1000, 9999).'.jpg';
            file_put_contents('book_img/'.$name,$image);
        } catch (Exception $e) {
            $image = '';
            $name = '';
        }
        $insert = [
            'title'      => $data['title'],
            'author'     => $data['author'],
            'sortid'     => $data['sortid'],
            'keywords'   => $data['key_words'] ?? '',
            'finish'     => $data['finish'] ?? 1,
            'desc'       => $data['desc'],
            'channel'    => $data['channel'] ?? 1,
            'image'      => 'book_img/'.$name,
            'created_at' => date( "Y-m-d H:i:s", time()),
            'updated_at' => date( "Y-m-d H:i:s", time()),
        ];

        $books_id = (new AdminModel('books'))->addGetId($insert);
        $res  = (new AdminModel('import_book'))->addArray(['articleid'=>$data['articleid'],'created_at'=>date( "Y-m-d H:i:s", time()),'local_articleid'=>$books_id]);
       
        return apiReturn(1, 'success');
    }

    public function addChapter(Request $r) {
        $articleid = $r->input('articleid');
        $data = $r->input();
        if(empty($data['subhead'])) return apireturn(0, 'subhead is null');
        if(empty($data['order']))   return apireturn(0, 'order is null');
        if(empty($data['content']))   return apireturn(0, 'content is null');

        $res  = (new AdminModel('import_book'))->getOne(['articleid'=>$data['articleid']]);
        if(empty($res['local_articleid'])) apiReturn('0','articleid is not exist');
        $local_articleid = $res['local_articleid'];
        $res  = (new AdminModel('import_chapters'))->getOne(['articleid'=>$data['articleid'],'chapterid'=>$data['chapterid']]);
        if($res) apiReturn('0','chapterid repeat');
        // var_dump($local_articleid);die;
        $chapterInfo = [
            'subhead' => $data['subhead'],
            'is_vip' => $data['is_vip'] ?? 0,
            'displayorder' => $data['order'],
            'created_at' => date( "Y-m-d H:i:s", time()),
            'updated_at' => date( "Y-m-d H:i:s", time()),
            'book_id' => $local_articleid,
            'word_count' => mb_strlen($data['content']),
        ];

        $local_chapterid = (new AdminModel('chapters'))->addGetId($chapterInfo);
        $res  = (new AdminModel('import_chapters'))->addArray(['articleid'=>$data['articleid'],'created_at'=>date( "Y-m-d H:i:s", time()),'chapterid'=>$data['chapterid'],'local_chapterid'=>$local_chapterid]);
        $tableName = 'chapter_'.$local_chapterid%100;
        $res = (new AdminModel($tableName))->addArray([
                'id' => $local_chapterid,
                'content' => $data['content'],
                'created_at' => date( "Y-m-d H:i:s", time()),
                'updated_at' => date( "Y-m-d H:i:s", time()),
        ]);
        (new AdminModel('books'))->getDb()->where('id',$local_articleid)->increment('word_count',mb_strlen($data['content']));
        if($res) apiReturn(1, 'success');
        apiReturn(0, 'error');
    }

    public function getChapterList(Request $r) {
        $articleid = $r->input('articleid');
        $res  = (new AdminModel('import_book'))->getOne(['articleid'=>$articleid]);
        if(empty($res['local_articleid'])) apiReturn('0','articleid is not exist');
        $local_articleid = $res['local_articleid'];
        $chapterList = (new AdminModel('chapters'))->getDb()->rightJoin('import_chapters as b',['b.local_chapterid'=>'chapters.id'])->where(['book_id'=>$local_articleid])->orderBy('displayorder','asc')->get();
        $chapterList = json_decode(json_encode($chapterList),true);
        $return = [];
        foreach ($chapterList as $key => $value) {
            $return[] = [
                'subhead' => $value['subhead'],
                'order' => $value['displayorder'],
                'chapterid' => $value['chapterid'],
            ];
        }
        return apiReturn(1, 'success', $return);
    }
    
}