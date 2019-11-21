<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use App\Models\FavoriteModel;
use App\Models\CommonModel;
use App\Services\BookService;
use App\Services\PayService;
use App\Services\TaskService;
use App\Services\UserService;

/**
 * @group 书架
 */
class BookShelfController extends BaseController
{
    protected $book;
    protected $pay;
    protected $task;
    protected $user;
    public function __construct(BookService $book, PayService $pay, TaskService $task,UserService $user) {
        $this->book = $book;
        $this->pay  = $pay;
        $this->task = $task;
        $this->user = $user;
    }

    /**
     * 获取书架内容 api/book/shelf
     * 成功返回1 失败返回0+msg
     * @response {"code":1,"msg":"success","data":{"lastRead":{"articleid":1,"title":"\u7b2c\u4e8c\u5341\u4e09\u7ae0 \u6211\u4ee5\u4e3a\u4f60\u77e5\u9053\u6211\u7684\u5f02\u7980\u2026\u2026","image":"http:\/\/127.0.0.1\/book_pic\/18b45bbcaab3ac3d75cf6e250e5f3e66.jpeg","ratio":24,"chapterid":24},"shelf":[{"image":"http:\/\/127.0.0.1\/book_pic\/18b45bbcaab3ac3d75cf6e250e5f3e66.jpeg","title":"\u5c06\u591c","articleid":1}],"rec":[{"image":"http:\/\/127.0.0.1\/\/Users\/wangxin\/Desktop\/test_dir\/15583423582532.jpeg","title":"\u674e\u83b2\u82b1ya","articleid":9},{"image":"http:\/\/127.0.0.1\/book_pic\/18b45bbcaab3ac3d75cf6e250e5f3e66.jpeg","title":"\u5c06\u591c","articleid":1}]}}
     * @response {"code":0,"msg":"添加失败","data":{}}
     */
    public function bookShelf(Request $r) {
        $userid = '';
        if(!empty($r->header('token'))) {
            $userid = getUserId($r->header('token'));
        }
        $return['lastRead'] = $this->getLastRead($userid);
        $favorite = (new FavoriteModel())->getUserFavor(['userid'=>$userid]);
        $books = $this->book->getBookInfo($favorite);
        if(!empty($books)) {
            foreach ($books as $key => $value) {
                $books[$key]['is_auto_paid'] = $this->book->getAutoPaid($userid, $value['id']) ? 1 : 0;
            }
        }
        $return['shelf'] = $this->makeReturn($books);

        //随机推荐书
        $rand = $this->getRecBook($userid);
        $rand['data'] = $this->makeReturn($rand['data']);
        $return['rec'] = $rand;

        apiReturn(1, 'success', $return);
    }

    public function makeReturn($data) {
        $res = [];
        if(empty($data)) return $res;
        foreach ($data as $key => $value) {
            $res[] = [
                'image' => $value['image'],
                'title' => $value['title'],
                'articleid' => $value['id'],
                'is_auto_paid' => $value['is_auto_paid'],
                'type'  => $value['type'],
            ];
        }
        return $res;
    }

    /**
     * 加入书架 book/shelf/add
     * 成功返回1 失败返回0+msg
     * @bodyParam articleid int required 书籍id
     * @response {"code":1,"msg":"success","data":{}}
     * @response {"code":0,"msg":"添加失败","data":{}}
     */
    public function addShelf(Request $r) {
        $userid = getUserId($r->header('token'));
        $articleid = $r->input('articleid');
        $created_at = time();
        //check
        $isSet = (new FavoriteModel())->getUserFavor(['userid'=>$userid,'articleid'=> $articleid]);
        if(empty($isSet)) {
            //add
            $favorite = (new FavoriteModel())->addFavor(['userid'=>$userid,'articleid'=> $articleid,'created_at'=>date('Y-m-d H:i:s',time()),'last_read_time'=>date('Y-m-d H:i:s',time())]);
            apiReturn(1, '添加成功');
        }
        apiReturn(0, '已经添加过了');
    }
    
    public function getLastRead($userid) {
        $lastRead = $this->book->getLastReadOne($userid);
        $return = [
            'articleid' => 0,
            'title'     => '',
            'image'     => '',
            'ratio'     =>  0,
            'chapterid' => 0,
            'is_auto_paid' => 0,
            'chapter_name' => '',
            'type'      => 1,
        ];
        if(!$lastRead) {
            return $return;
        } 
        $bookInfo = $this->book->getBookInfo($lastRead['articleid']);
        if(empty($bookInfo)) return $return;
        //计算百分比
        $chapterid = $lastRead['chapterid'];
        $chapterInfo = $this->book->getChapterInfo($chapterid);

        $count = $this->book->getChapterCount($lastRead['articleid']);
        if(empty($count)) $ratio = 0;
        else $ratio = ceil($chapterInfo['displayorder']/$count*100);
        if($ratio > 100) $ratio = 100;

        $return = [
            'articleid' => $bookInfo['id'],
            'title'     => $bookInfo['title'],
            'image'     => $bookInfo['image'],
            'type'      => $bookInfo['type'],
            'ratio'     => $ratio,
            'chapterid' => $chapterInfo['id'],
            'chapter_name' => $chapterInfo['subhead'],
        ];
        $return['is_auto_paid'] = $this->book->getAutoPaid($userid, $bookInfo['id']) ? 1 : 0;
        return $return;
    }

    public function getRecBook($userid = '') {
        // $book = $this->book->getRandBook();
        $conn = ['image','id','title','desc','author','keywords','type','finish','word_count','sortid'];
        $isvip = 0;
        if(!empty($userid)) {
            $userInfo = $this->user->getUserInfo(['id'=>$userid]);
            $isvip = $userInfo['is_vip'];
        }
        $channel = 99999;
        if($isvip) $channel = 100000;
        $recType = (new CommonModel("recommend_type"))->getOne(['id'=>$channel]);

        $book = (new CommonModel('book_recommend'))->getAll(['rec_id'=>$channel]);
        $book = $this->book->getBookInfo($book, $conn);
        if($book) {
            foreach ($book as $key => $value) {
                if($userid) $book[$key]['is_auto_paid'] = $this->book->getAutoPaid($userid, $value['id']) ? 1 : 0;
                else $book[$key]['is_auto_paid'] = 0;

                // $book[$key]['image'] = imageUrl($value['image']);
            }
            return [ 'title'=>$recType['name'],'data'=>$book];
        }
        return false;
    }

    public function sign(Request $r) {
        $userid = getUserId($r->header('token'));
        //check is sign
        $where = [
            'date' => date("Ymd",time()),
            'userid' => $userid,
        ];
        $res = (new CommonModel('user_sign'))->getOne($where);
        if(empty($res)) {
            $data = $where;
            $config = (new CommonModel('config'))->getOne();
            $coin = $config['sign_coin'];
            $data['created_at'] = date("Y-m-d H:i:s",time());
            $data['coin'] = $coin;
            $res = (new CommonModel('user_sign'))->addArray($data);

            //增加余额
            $this->pay->expend($userid, $coin, '每日签到', $type = 1);
            $this->task->addTaskLog($userid, 1);
            if($res) apiReturn(1,'签到成功',['coin'=>$coin]);
        }
        apiReturn(0, '已经签过到了');

    }

    /**
     * 书架删除 book/shelf/del
     * 成功返回1 失败返回0+msg
     * @bodyParam articleid int required 书籍id  多个id用逗号隔开
     * @response {"code":1,"msg":"success","data":{}}
     * @response {"code":0,"msg":"error","data":{}}
     */
    public function delShelf(Request $r) {
        $userid = getUserId($r->header('token'));
        $articleid = explode(',',$r->input('articleid'));
        $res = (new FavoriteModel())->delFavor($userid, $articleid);
        if($res) return apiReturn(1, '删除成功');
        return apiReturn(0,'删除失败');
    }
}
