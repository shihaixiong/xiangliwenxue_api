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
 * 书籍管理
 *
 */
class BookController extends BaseController
{ 

    //书籍列表
    public function bookIndex(Content $content,Request $r){
        return $content->body(view('admin/book/book_index',['search'=>$r->input('title')]));
    }
    public function bookGetIndex(Content $content,Request $r){

        $model =(new AdminModel('books'));
        if($r->title) {
             $data['data'] = $model->getAll([['title','like',"%".$r->title."%"],['type','=',1]]);
        }else{
            $data['data'] = $model->getAll([['type','=',1]]);
        }

        echo json_encode($data);

    }

    //添加书籍
    public function createBook(Content $content,Request $r){
        return $content->body(view('admin/book/book_add'));
    }
    public function addBook(Content $content,Request $r){

    	$data['title']      = $r->title;
    	$data['desc']       = $r->desc;
    	$data['author']     = $r->author;
    	$data['sortid']     = $r->sortid;
    	$data['keywords']   = $r->keywords;
    	$data['word_count'] = $r->word_count;
    	$data['finish']     = $r->finish;
        $data['visible']    = $r->visible;
        $data['is_vip']     = $r->is_vip;
        $data['channel']    = $r->channel;

    	if($r['pic']){
            $image = $this->uploadFiles($r['pic'],'book_img/');
            $data['image']=$image['newname'];
        }
        $data['created_at'] = date('Y-m-d h:i:s',time());
        $data['updated_at'] = date('Y-m-d h:i:s',time());

        (new AdminModel('books'))->addArray($data);

        return redirect('/admin/book/bookIndex');
    }

    //编辑书籍
    public function editBook(Content $content,Request $r){

    	$id = $r->id;
    	$arr = (new AdminModel('books'))->getOne(['id'=>$id]);
        return $content->body(view('admin/book/book_edit')->with('data',$arr));
    }
    public function updateBook(Content $content,Request $r){

    	$data['title'] 		= $r->title;
    	$data['desc'] 		= $r->desc;
    	$data['author'] 	= $r->author;
    	$data['sortid'] 	= $r->sortid;
    	$data['keywords'] 	= $r->keywords;
    	// $data['word_count'] = $r->word_count;
    	$data['finish'] 	= $r->finish;
        $data['visible']    = $r->visible;
        $data['is_vip']     = $r->is_vip;
        $data['channel']    = $r->channel;
        $data['price']      = $r->price;
        $data['start_num']  = $r->start_num;
        $data['end_num']    = $r->end_num;
        $bookInfo = (new AdminModel('books'))->getOne(['id'=>$r->id]);
        if($data['price'] != $bookInfo['price']) {
            DB::update("update chapters set price=if(ceiling(word_count*$r->price/1000) is null,0,ceiling(word_count*$r->price/1000)) where book_id=$r->id");
        }
    	if($r['pic']){
            $image = $this->uploadFiles($r['pic'],'book_img/');
            $data['image']=$image['newname'];
        }
       
        $data['updated_at'] = date('Y-m-d h:i:s',time());

        $id = $r->id;

        (new AdminModel('books'))->editArray(['id'=>$id],$data);

        return redirect('/admin/book/bookIndex');
    }

    //删除书籍
    public function delBook(Content $content,Request $r){

        $id = $r->id;

        $res = (new AdminModel('books'))->delArray(['id'=>$id]);
        $chapters = (new AdminModel('chapters'))->getAll(['book_id'=>$id]);
        $ids = [];
        foreach ($chapters as $v) {
            $ids[$v['id']%100][] = $v['id'];
        }
        foreach ($ids as $k=>$v) {
            $table = "chapter_".$k;
            $res = (new AdminModel($table))->getDb()->whereIn('id',$v)->delete();
        }

        $res = (new AdminModel('chapters'))->delArray(['book_id'=>$id]);
        $importBook = (new AdminModel('import_book'))->getOne(['local_articleid'=>$id]);
        $res = (new AdminModel('import_book'))->delArray(['local_articleid'=>$id]);
        $res = (new AdminModel('import_chapters'))->delArray(['articleid'=>$importBook['articleid']]);

        return redirect('/admin/book/bookIndex');

    }

    //章节列表
    public function chapterIndex(Content $content,Request $r){

        return $content->body(view('admin/book/chapter_index'));
    }
    public function chapterGetIndex(Content $content,Request $r){

    	$book_id = $r->book_id;

        $data['data']=(new AdminModel('chapters'))->getAll(['book_id'=>$book_id]);

        echo json_encode($data);

    }

    //新增章节
    public function createChapter(Content $content,Request $r){

    	$book_id = $r->book_id;
        return $content->body(view('admin/book/chapter_add')->with('book_id',$book_id));

    }
    public function addChapter(Content $content,Request $r){

        //总字数

    	$data['subhead'] = $r->subhead;
    	// $data['word_count'] = $r->word_count;
    	// $data['visible'] = $r->visible;
    	$data['is_vip'] = $r->is_vip;
    	$book_content = $r->book_content;
    	$data['book_id'] = $r->book_id;

        $data['word_count'] = mb_strlen($book_content,"utf-8");


        //排序
    	$count = (new AdminModel('chapters'))->getCount(['book_id'=>$data['book_id']]);
        $bookInfo = (new AdminModel('books'))->getOne(['id'=>$data['book_id']]);
        $data['displayorder'] = $count+1;
        $data['price'] = ceil($data['word_count']*$bookInfo['price']/1000);
        $data['created_at'] = date('Y-m-d h:i:s',time());
        $data['updated_at'] = date('Y-m-d h:i:s',time());

        $sum = (new AdminModel('chapters'))->getSum('word_count',['book_id'=>$data['book_id']]);


        $chapter_id = (new AdminModel('chapters'))->addGetId($data);

        $chapter_table =  'chapter_'.$chapter_id%100;

        (new AdminModel($chapter_table))->addArray([
        	'id'=>$chapter_id,
        	'content'=>$book_content,
        	'created_at'=>$data['created_at'],
        	'updated_at'=>$data['updated_at']
        ]);

        $count = (new AdminModel('chapters'))->getCount(['book_id'=>$data['book_id']]);
        
        

        (new AdminModel('books'))->editArray(['id'=>$data['book_id']],['word_count'=>$sum+$data['word_count']]);


        return redirect('/admin/book/chapterIndex?book_id='.$data['book_id']);
       

    }

    //编辑章节
    public function editChapter(Content $content,Request $r){


    	$id      = $r->id;
    	$chapter_table =  'chapter_'.$id%100;
    	$arr = (new AdminModel('chapters'))->getOne(['id'=>$id]);

    	$arr2 = (new AdminModel($chapter_table))->getOne(['id'=>$id],'content');

    	$arr['content']=$arr2['content'];
        return $content->body(view('admin/book/chapter_edit')->with('data',$arr));

    }
    public function updateChapter(Content $content,Request $r){

    	$data['subhead'] 	= $r->subhead;
    	// $data['word_count'] = $r->word_count;
    	// $data['visible'] 	= $r->visible;
    	$data['is_vip'] 	= $r->is_vip;
    	$book_content 		= $r->book_content;
    	$data['book_id']	= $r->book_id;
        $data['word_count'] = mb_strlen($book_content,"utf-8");
        $data['price']   = $r->price;

    	$id = $r->id;

        $data['updated_at'] = date('Y-m-d h:i:s',time());

        

        (new AdminModel('chapters'))->editArray(['id'=>$id],$data);

        $chapter_table =  'chapter_'.$id%100;

        (new AdminModel($chapter_table))->editArray(['id'=>$id],[
        	'content'=>$book_content,
        	'updated_at'=>$data['updated_at']
        ]);

        $sum = (new AdminModel('chapters'))->getSum('word_count',['book_id'=>$data['book_id']]);
        
        (new AdminModel('books'))->editArray(['id'=>$data['book_id']],['word_count'=>$sum]);

        return redirect('/admin/book/chapterIndex?book_id='.$data['book_id']);
       

    }

    //删除书籍
    public function delChapter(Content $content,Request $r){

        $id 	 = $r->id;
        $book_id = $r->book_id;

        (new AdminModel('chapters'))->delArray(['id'=>$id]);
        
        $chapter_table =  'chapter_'.$id%100;

        (new AdminModel($chapter_table))->delArray(['id'=>$id]);

        return redirect('/admin/book/chapterIndex?book_id='.$book_id);

    }

    //分类select
    public function bookSort(Request $r){
        $arr = (new AdminModel('book_sort'))->getAll();
        return json_encode($arr);
    }

    //频道select
    public function bookChannel(Request $r){
        $arr = (new AdminModel('book_channel'))->getAll();
        return json_encode($arr);
    }

    public function bookExport(Request $r) {
        $articleid = $r->input('articleid');
        $bookInfo = (new AdminModel('books'))->getOne(['id'=>$articleid]);
        $title = $bookInfo['title'];
        $chapterlist = (new AdminModel('chapters'))->getAll(['book_id'=>$articleid]);
        $dir = 'export_text/'.$title.'.txt';
        file_put_contents($dir,'');
        foreach ($chapterlist as $key => $value) {
            $table = "chapter_".$value['id']%100;
            $content = (new AdminModel($table))->getOne(['id'=>$value['id']]);
            file_put_contents('export_text/'.$title.'.txt',"=====".$value['subhead']."\n===",FILE_APPEND);
            file_put_contents('export_text/'.$title.'.txt',$content['content'],FILE_APPEND);
        }

        header("Content-type:application/octet-stream"); 
        $filename = basename($dir);
        header("Content-Disposition:attachment;filename = ".$title.'.txt'); 
        header("Accept-ranges:bytes"); 
        header("Accept-length:".filesize($dir)); 
        readfile($dir);

    }

    public function updateBookLog(Content $content) {
        $data = (new AdminModel('update_book_log'))->getDb()->orderBy('created_at','desc')->paginate(20);
        return $content->body(view('admin/book/update_book_log',['data'=>$data]));

    }

    public function getBookId(Request $r) {
        $title = $r->input('title');
        $data = (new AdminModel('books'))->getOne(['title'=>$title]);
        if(empty($data)) apiReturn(0, 'error', []);
        apiReturn(1,'success',$data);
    }
}