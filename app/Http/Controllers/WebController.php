<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Services\FuncService;
use App\Services\BookService;
use App\Models\CommonModel;
use Cache;
use Illuminate\Support\Facades\Mail;

/**
 * @group 用户
 */
class WebController extends BaseController
{
    protected $user;
    protected $func;
    protected $book;
    public function __construct(UserService $user, FuncService $fun, BookService $book) {
        $this->user = $user;
        $this->func = $fun;
        $this->book = $book;
    }
    
    public function index(Request $r) {
        return view('web/index');
    }

    public function book_list(Request $r) {
        $channel = $r->input('channel') ?? 1;
        $sort = (new CommonModel('book_sort'))->getAll();
        $sorts = [];
        foreach($sort as $v) {
            if($v['channel'] == $channel) $sorts[] = $v;
        }
        $sortid = $r->input('sortid') ?? 1;
        $data = (new CommonModel('books'))->getDb()->where(['channel'=>$channel,'sortid'=>$sortid])->limit(10)->get();
        $data = json_decode(json_encode($data),true);
        foreach ($data as $key => $value) {
            $data[$key]['image'] = imageUrl($value['image']);
        }
        $data = [
            'sort' => $sorts,
            'channel' => $channel,
            'sortid' => $sortid,
            'data' => $data, 
        ];
        return view('web/book_list', $data);
    }

    public function getMore(Request $r) {
        $page = $r->input('page');
        $offset = ($page - 1) * 10;
        $channel = $r->input('channel') ?? 1;
        $sort = (new CommonModel('book_sort'))->getAll();
        $sortid = $r->input('sortid') ?? 1;
        $data = (new CommonModel('books'))->getDb()->where(['channel'=>$channel,'sortid'=>$sortid])->limit(10)->offset($offset)->get();
        $data = json_decode(json_encode($data),true);
        foreach ($data as $key => $value) {
            $data[$key]['image'] = imageUrl($value['image']);
        }
        apiReturn(1,'success',$data);
    }

    public function chapter_list(Request $r) {
        $articleid = $r->input('articleid');
        $data = (new CommonModel('chapters'))->getDb()->where('book_id',$articleid)->orderBy('displayorder','asc')->get();
        $data = json_decode(json_encode($data),true);

        return view('web/chapter_list',['data'=>$data]);
    }

    public function book_detail(Request $r) {
        $articleid = $r->input('articleid');
        $book = (new CommonModel('books'))->getOne(['id'=>$articleid]);
        $startNum = $book['start_num'];
        $chapterid = ($r->input('chapterid') ?? 1) - 1;
        if($chapterid+1 > $startNum + 2 ) {
            return redirect('qrocde');            
        }

        if($chapterid+1 < $startNum) {
            return redirect('qrocde');            
        }

        $data = (new CommonModel('chapters'))->getDb()->where('book_id',$articleid)->orderBy('displayorder','asc')->get();
        $data = json_decode(json_encode($data),true);
        $count = count($data);
        $ths = [];
        $last = [];
        $next = [];
        foreach ($data as $key => $value) {
            if($key == $chapterid) $ths = $value;
            if($key == $chapterid-1) $last = $value;
            if($key == $chapterid+1) $next = $value;
        }
        // if($ths['is_vip'] == 1) {
        //     return redirect('qrocde');
        // }
        $tableName = 'chapter_'.$ths['id'] % 100;
        $content = (new CommonModel($tableName))->getOne(['id'=>$ths['id']]);
        $data = [];
        // $data['content'] = str_replace("\n","<br/>",$content['content']);
        $data['content'] = str_replace("\n","</p><p class='book-content'>",$content['content']);

        $data['title'] = $book['title'];
        $data['subhead'] = $ths['subhead'];
        $data['chapterid'] = $chapterid+1;
        $data['next'] = $next['subhead'] ?? '';
        $data['last'] = $last['subhead'] ?? '';
        $data['articleid'] = $r->input('articleid');
        return view('web/book_details',['data'=>$data]);
    }


    public function book_share(Request $r) {
        $articleid = $r->input('articleid');
        $book = (new CommonModel('books'))->getOne(['id'=>$articleid]);
        $startNum = $book['start_num']-1;
        $endNum = $book['end_num'] ?? 3;
        $chapterid = $startNum;
        $language = $r->input('language') ?? 0;

        $data = (new CommonModel('chapters'))->getDb()->where('book_id',$articleid)->orderBy('displayorder','asc')->offset($startNum)->limit($endNum)->get();
        $data = json_decode(json_encode($data),true);
        $count = count($data);
        $ths = [];
        $last = [];
        $next = [];
        $content = [];
        if($book['type'] == 1) {
            foreach ($data as $key => $value) {
                $tableName = 'chapter_'.$value['id'] % 100;
                $chapterInfo = (new CommonModel($tableName))->getOne(['id'=>$value['id']]);
                $chapterInfo['content'] = str_replace("\r","",$chapterInfo['content']);
                $chapterInfo['content'] = str_replace(" ","",$chapterInfo['content']);
                $chapterInfo['content'] = str_replace("\n","</p><p class='p1'>",$chapterInfo['content']);
                // $chapterInfo['content'] = preg_replace("/\n/","<br/>",$chapterInfo['content']);
                $content[] = ['content'=> $chapterInfo['content'] ?? '','subhead'=>$value['subhead']];
            }
        }else{
            foreach ($data as $key => $value) {
                $tableName = 'chapter_'.$value['id'] % 100;
                $chapterInfo = (new CommonModel($tableName))->getOne(['id'=>$value['id']]);
                $chapterInfo['content'] = json_decode($chapterInfo['content'],true);
                $content[] = ['content'=> $chapterInfo['content'] ?? '','subhead'=>$value['subhead']];
            }
        }
        // if($ths['is_vip'] == 1) {
        //     return redirect('qrocde');
        // }
        $data = [];
        $data['content'] = $content;
        $data['title'] = $book['title'];
        $data['type'] = $book['type'];
        $data['htmlTitle'] = '香狸文学';
        $data['author'] = $book['author'];
        $data['desc'] = $book['desc'];
        $data['finish'] = $book['finish'];
        $data['image'] = imageUrl($book['image']);
        $data['chapterid'] = $chapterid+1;
        $data['articleid'] = $r->input('articleid');
        $data['msg'] = "阅读更多内容<br/>查看完本内容下载香狸文学";
        if($language == 1) $data = makeData($data);
        return view('web/share',['data'=>$data]);
    }

    public function tui(Request $r) {
        $articleid = $r->input('articleid');
        $book = (new CommonModel('books'))->getOne(['id'=>$articleid]);
        $startNum = $book['start_num']-1;
        $endNum = $book['end_num'] ?? 3;
        $chapterid = $startNum;
        $language = $r->input('language') ?? 0;

        $data = (new CommonModel('chapters'))->getDb()->where('book_id',$articleid)->orderBy('displayorder','asc')->offset($startNum)->limit($endNum)->get();

        $data = json_decode(json_encode($data),true);
        $count = count($data);
        $ths = [];
        $last = [];
        $next = [];
        $content = [];
        foreach ($data as $key => $value) {
            $tableName = 'chapter_'.$value['id'] % 100;
            $chapterInfo = (new CommonModel($tableName))->getOne(['id'=>$value['id']]);
            $chapterInfo['content'] = str_replace("\n","<br/>",$chapterInfo['content']);
            $chapterInfo['content'] = str_replace("\r","",$chapterInfo['content']);
            $chapterInfo['content'] = str_replace(" ","",$chapterInfo['content']);
            $chapterInfo['content'] = str_replace("<br/><br/><br/>","<br/>　　",$chapterInfo['content']);
            // $chapterInfo['content'] = preg_replace("/\n/","<br/>",$chapterInfo['content']);
            $content[] = ['content'=> $chapterInfo['content'] ?? '','subhead'=>$value['subhead']];
        }
        // if($ths['is_vip'] == 1) {
        //     return redirect('qrocde');
        // }
        $data = [];
        $data['content'] = $content;
        $data['title'] = $book['title'];
        $data['htmlTitle'] = '小鸡读书';
        $data['author'] = $book['author'];
        $data['desc'] = $book['desc'];
        $data['finish'] = $book['finish'];
        $data['image'] = imageUrl($book['image']);
        $data['chapterid'] = $chapterid+1;
        $data['articleid'] = $r->input('articleid');
        $data['msg'] = "阅读更多内容<br/>查看完本内容下载小鸡读书";
        if($language == 1) $data = makeData($data);
        return view('web/share',['data'=>$data]);
    }

    public function qrcode(Request $r) {
        return view('web/qr_code');
    }

    public function spread(Request $r) {
        $id = $r->id;
        $data = $spread = (new CommonModel('spread'))->getOne(['id'=>$id]);
        if(empty($data)) {
            return ;
        }
        $res = (new CommonModel('spread'))->getDb()->where(['id'=>$id])->increment('view_num',1);
        $res = (new CommonModel('spread_count'))->getOne(['sid'=>$id,'date'=>date("Ymd")]);
        if(empty($res)) {
            $res = (new CommonModel('spread_count'))->getDb()->where(['sid'=>$id,'date'=>date("Ymd")])->insert(['view_num'=>1,'date'=>date("Ymd"),'sid'=>$id]);

        }else{
            $res = (new CommonModel('spread_count'))->getDb()->where(['sid'=>$id,'date'=>date("Ymd")])->increment('view_num',1);
            
        }
        $style = $data['style'];
        $articleid = $data['articleid'];
        $images = imageUrl($data['first_img']);
        $img = json_decode($data['images'],true);
        $image = [];
        foreach($img as $v){
            $image[] = imageUrl($v);
        }
        $book = (new CommonModel('books'))->getOne(['id'=>$articleid]);
        $startNum = $data['start_num']-1;
        $endNum = $data['show_num'] ?? 3;
        $chapterid = $startNum;
        $language = $r->input('language') ?? 0;

        $data = (new CommonModel('chapters'))->getDb()->where('book_id',$articleid)->orderBy('displayorder','asc')->offset($startNum)->limit($endNum)->get();

        $data = json_decode(json_encode($data),true);
        $count = count($data);
        $ths = [];
        $last = [];
        $next = [];
        $content = [];
        foreach ($data as $key => $value) {
            $tableName = 'chapter_'.$value['id'] % 100;
            $chapterInfo = (new CommonModel($tableName))->getOne(['id'=>$value['id']]);
            $chapterInfo['content'] = str_replace("\n","<br/>",$chapterInfo['content']);
            $chapterInfo['content'] = str_replace("\r","",$chapterInfo['content']);
            $chapterInfo['content'] = str_replace(" ","",$chapterInfo['content']);
            $chapterInfo['content'] = str_replace("<br/><br/><br/>","<br/>　　",$chapterInfo['content']);
            // $chapterInfo['content'] = preg_replace("/\n/","<br/>",$chapterInfo['content']);
            $content[] = ['content'=> $chapterInfo['content'] ?? '','subhead'=>$value['subhead']];
        }
        $logo = [1,2,3,4,5,6,7,8,9,10,11,12,13];
        $res = array_rand($logo,8);
        $tmp = [];
        foreach($res as $v) {
            $tmp[] = imageUrl("tmp_user_logo/".$logo[$v].".jpg");
        }
        $return = [
            'content' => $content,
            'image'   => $images,
            'images'  => $image,
            'id'      => $id,
            'spread'  => $spread,
            'logo'    => $tmp,
            'header_title' => "小鸡读书",
            'footer'    => '更多精彩原文尽在小鸡读书',
            'msg'     => '人在线浏览',
        ];
        if($spread['language'] == 2) {
            $return = makeData($return);
        }
        if($style == 1) {
            return view('web/spread1',$return);

        }else{
            return view('web/spread2',$return);

        }
    }

    public function downUrl(Request $r) {
        //全部变成小写字母
        $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
        //分别进行判断
        if(strpos($agent, 'iphone') || strpos($agent, 'ipad')) {
            $url = 'https://play.google.com/store/apps/details?id=com.xj.read';
            echo "ios即将上线,敬请期待";
            die;
        } else {
            $downConfig = (new CommonModel('down_config'))->getDb()->orderBy('id','desc')->first();
            $url =  env("WEB_URL")."apk/".$downConfig->file;
            $url = "http://wap.xiangliwenxue.com/";
            return redirect($url);
        }
    }

    public function agreement(Request $r) {
        return view('web/agreement');
    }

    public function agreement2(Request $r) {
        return view('web/agreement2');
    }
    
}
