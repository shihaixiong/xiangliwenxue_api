<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use App\Services\BookService;
use App\Services\UserService;
use App\Services\PayService;
use App\Models\CommonModel;
use App\Models\FavoriteModel;
use DB;
/**
 * @group 阅读
 */
class BookController extends BaseController
{
    protected $book;
    protected $user;

    public function __construct(
        BookService $book, 
        UserService $user,
        PayService $pay
    ) {
        $this->book = $book;
        $this->user = $user;
        $this->pay  = $pay;
    }
    /**
     * 章节目录 api/book/chapters
     * 成功返回1 失败返回0+msg
     * @bodyParam articleid int required 书籍id
     * @response {"code":1,"msg":"success","data":[{"subhead":"\u5f00\u5934","is_vip":0,"word_count":4737,"chapterid":1},{"subhead":"\u7b2c\u4e00\u7ae0 \u6e2d\u57ce\u6709\u96e8\uff0c\u5c11\u5e74\u6709\u4f8d","is_vip":0,"word_count":3704,"chapterid":2},{"subhead":"\u7b2c\u4e8c\u7ae0 \u80fd\u4e66\u80fd\u8a00\u7a77\u9178\u5c11\u5e74","is_vip":0,"word_count":3631,"chapterid":3}]}
     */
    public function chapters(Request $r) {
        $bookid = $r->input('articleid');
        $chapters = $this->book->getChapters($bookid);
        $paid = [];
        if(!empty($r->header('token'))) {
            $userid = getUserId($r->header('token'));
            $paidList = $this->book->getUserArticlePaid($userid, $bookid);
            if(!empty($paidList)){
                foreach ($paidList as $key => $value) {
                    $paid[] = $value['chapterid'];
                }
            }
        }
        $bookInfo = $this->book->getBookInfo($bookid);
        $isvip = $bookInfo['is_vip'];

        foreach ($chapters as $key => $value) {
            if($isvip == 0) {
                $chapters[$key]['is_vip'] = 0;
                continue;
            }

            if(in_array($value['chapterid'], $paid)) {
                $chapters[$key]['is_paid'] = 1;
            }else{
                $chapters[$key]['is_paid'] = 0;
            }

        }
        apiReturn( 1, '成功', $chapters);
    }

    /**
     * 章节内容 api/book/content
     * 成功返回1 失败返回0+msg
     * @bodyParam articleid int required 书籍id
     * @bodyParam chapterid int required 章节id
     * @response {"code":1,"msg":"success","data":{"subhead":"标题","content":"正文","chapter":32}}
     * @response {"code":2,"msg":"\u6682\u672a\u8ba2\u9605","data":{"remain":0,"price":18,"chapter":32,"subhead":"\u7b2c\u4e09\u5341\u4e00\u7ae0 \u4e00\u6587\u94b1\u96be\u6b7b\u4e3b\u4ec6\u4fe9\uff08\u4e0a\uff09"}}
     */
    public function chapter(Request $r) {
        $bookid = $r->input('articleid');
        $chapterid = $r->input('chapterid');
        $chapter = $this->book->getChapter($bookid, $chapterid);
        $chapterInfo = $this->book->getChapterInfo($chapterid);
        //添加最近阅读
        if(!empty($r->header('token'))) {
            $userid = getUserid($r->header('token'));
            $this->book->addReadHistory($userid, $bookid, $chapterid);
            (new FavoriteModel())->updFavor(['articleid'=>$bookid,'userid'=>$userid],['last_read_time'=>date( "Y-m-d H:i:s", time())]);
        }
        $bookInfo = $this->book->getBookInfo($bookid);
        if($chapterInfo['is_vip'] == 1 && $bookInfo['is_vip'] != 0) {
            $price = $chapterInfo['price'] ?? (ceil($chapterInfo['word_count'] / 1000) * 6);
            $return = [
                'remain'    => 0,
                'price'     => $price,
                'chapterid' => $chapterInfo['id'],
                'subhead'   => $chapterInfo['subhead'],
                'content'   => mb_substr($chapter['content'], 0, 100),
            ];
            //判断是否登录
            if(empty($r->header('token'))) {
                apiReturn(2, '暂未订阅', $return);
            }
            $userInfo = $this->user->getUserInfo(['id'=>$userid]);
            $return['remain'] = $userInfo['remain'];

            //检测是否订阅
            $isPaid = $this->book->checkPaid($userid, $chapterid);
            if(!$isPaid) {
                if($price > $userInfo['remain'])  apiReturn(2, '余额不足', $return);
                //检测是否自动订阅
                $isAutoPaid = $this->book->getAutoPaid($userid, $bookid);
                if($isAutoPaid) {
                    //自动订阅
                    $subject = "订阅". $chapterInfo['subhead'];

                    $res = $this->chapterPaid($userid, $price, $subject, $chapterInfo);
                    if(!$res) return apiReturn(0, '订阅失败');
                }else {
                    apiReturn(2, '暂未订阅', $return);
                }
            }
        }
        $return = [
            'subhead' => $chapterInfo['subhead'],
            'content' => $chapter['content'],
            'chapterid' => $chapter['id'],
        ];
        apiReturn( 1, '成功', $return);

    }
    
    /**
     * 订阅章节 api/book/paid
     * 成功返回1 失败返回0+msg
     * @bodyParam chapterid int required 章节id
     * @bodyParam auto_paid int required 是否自动订阅 1是 0 否
     * @response {"code":1,"msg":"success","data":{}"}}
     * @response {"code":0,"msg":"\u5df2\u7ecf\u8ba2\u9605\u8fc7\u4e86","data":{}}
     */
    public function paid(Request $r) {
        $userid = getUserId($r->header('token'));
        $chapterid = $r->input('chapterid');
        $autoPaid = $r->input('auto_paid');

        
        $userInfo = $this->user->getUserInfo(['id'=>$userid]);
        $chapterInfo = $this->book->getChapterInfo($chapterid);

        if($autoPaid) {
            $articleid = $chapterInfo['book_id'];
            $isAuto = $this->book->getAutoPaid($userid, $articleid);
            if(empty($isAuto)) $res = $this->book->addAutoPaid($userid, $articleid);
        }

        if($chapterInfo['is_vip'] == 0) apiReturn(0, '章节不需要订阅');
        
        $price = $chapterInfo['price'];
        if($price > $userInfo['remain'])  apiReturn( 0, '余额不足');

        $subject = "订阅". $chapterInfo['subhead'];
        //检测是否订阅过了
        $isPaid = $this->book->checkPaid($userid, $chapterid);
        if(!$isPaid) {
            $res = $this->chapterPaid($userid, $price, $subject, $chapterInfo);
            if($res) return apiReturn( 1, '订阅成功');
            apiReturn( 0, '订阅失败');

        }else {
            apiReturn( 0, '已经订阅过了');
        }

            
    }

    public function chapterPaid($userid, $price, $subject, $chapterInfo) {
        //开启事务
        DB::beginTransaction(); 
        //消费书币
        $expend = $this->pay->expend($userid, $price, $subject, 2, $chapterInfo['id'], $chapterInfo['book_id']);
        //订阅章节
        $paid = $this->book->paid($userid, $chapterInfo['id'], $chapterInfo['book_id']);
        //成功提交  失败回滚
        if($expend && $paid) {
            DB::commit();
            return true;
        } else {
            DB::rollBack();
            return false;
        }

    }

    /**
     * 书籍详情 api/book/detail
     * 成功返回1 失败返回0+msg
     * @bodyParam articleid int required 书籍id
     * @response {"code":1,"msg":"success","data":{"bookInfo":{"title":"\u5c06\u591c","articleid":1,"keyword":["\u5f02\u4e16"],"desc":"简介","author":"\u732b\u817b","word_count":3994319,"is_vip":1,"grade":"3.5"},"comment":[{"userid":1,"pid":2,"content":"\u554a\u54c8\u54c8 \u8bd5\u4e00\u8bd5","username":"\u54a3\u5f53","image":"","level":1},{"userid":1,"pid":1,"content":"\u54c8\u54c8\u54c8 \u8fd9\u53f7","username":"\u54a3\u5f53","image":"","level":1}],"relate":[{"image":"http:\/\/127.0.0.1\/\/Users\/wangxin\/Desktop\/test_dir\/15583423582532.jpeg","title":"\u674e\u83b2\u82b1\u591cya","articleid":9}]}}
     * @response {"code":0,"msg":"缺少必要参数","data":{}}
     */
    public function detail(Request $r) {
        $articleid = $r->input('articleid');
        if(empty($articleid)) return apiReturn(0,'缺少必要参数');
        $bookInfo = $this->book->getBookInfo($articleid);
        $chapterCount = $this->book->getChapterCount($articleid);
        $autoPaid = 0;
        $userid   = 0;
        $isShelf  = 0;
        $grade    = 0;
        if(!empty($r->header('token'))) {
            $userid = getUserid($r->header('token'));
            $res = $this->book->getAutoPaid($userid, $articleid);
            if($res) $autoPaid = 1;
            $favor = (new FavoriteModel())->getUserFavor(['userid'=>$userid,'articleid'=>$articleid]);
            $isShelf  = $favor ? 1: 0;
            $grade = $this->book->getUserGrade($userid, $articleid);
        }
        //获取评分
        $bookInfo['grade'] = $this->book->getGrade($articleid);
        $sort = (new CommonModel('book_sort'))->getOne(['id'=>$bookInfo['sortid']]);
        $keywords = [
            $sort['sort'], ($bookInfo['finish'] == 1 ? '完结': '连载')
        ];
        $lastChapter = (new CommonModel('chapters'))->getDb()->where('book_id',$bookInfo['id'])->orderBy('id','desc')->first();
        $return['bookInfo'] = [
            'title'      => $bookInfo['title'],
            'articleid'  => $bookInfo['id'],
            'keyword'    => $keywords,
            'desc'       => $bookInfo['desc'],
            'author'     => $bookInfo['author'],
            'word_count' => $bookInfo['word_count'],
            'is_vip'     => $bookInfo['is_vip'],
            'grade'      => number_format($bookInfo['grade'],1),
            'image'      => $bookInfo['image'],
            'finish'     => $bookInfo['finish'],
            'auto_paid'  => $autoPaid,//自动订阅
            'chapter_count' => $chapterCount,
            'grade_peo'  => $this->book->getGradeNum($articleid),
            'is_shelf'   => $isShelf,
            'user_grade' => $grade,
            'type'       => $bookInfo['type'],
            'last_chapter' => $lastChapter->subhead,
        ];

        //获取评论
        $comment = $this->book->getComment($articleid,3);
        $return['comment'] = $this->getCommentUser($comment, $userid);

        //获取相关推荐
        $relate = $this->getRecBook($bookInfo['sortid'],$articleid,$bookInfo['type']);
        $return['relate'] = $this->makeReturn($relate);
        apiReturn(1,'成功',$return);
    }
    
    public function getSort(Request $r){
        $sort = (new CommonModel('book_sort'))->getAll();
        apiReturn(1, '成功', $sort);
    }

    public function getCommentUser($comment, $userid = '') {
        $return = [];
        foreach ($comment as $key => $value) {
            if(empty($value['userid'])) continue;
            $userInfo = $this->user->getUserInfo(['id'=>$value['userid']]);
            $like = (new CommonModel('comment_like'))->getCount(['pid'=>$value['id']]);
            $num = (new CommonModel('comment'))->getCount(['pid'=>$value['id']]);
            $isLike = 0;
            if(!empty($userid)) {
                $res = (new CommonModel('comment_like'))->getOne([
                    'userid' => $userid,
                    'pid'    => $value['id'],
                ]);
                $isLike = $res ? 1: 0;
            }
            $return[] = [
                'userid'   => $userInfo['id'],
                'pid'      => $value['id'],
                'content'  => $value['content'],
                'username' => $userInfo['username'],
                'image'    => ImageUrl($userInfo['image']),
                'level'    => 1,
                'like'     => $like ?? 0,
                'comment_num' => $num ?? 0,
                'is_like'  =>  $isLike,
                'create_time' => $value['created_at'],
            ];
        }
        return $return;
    }

    public function getRecBook($sortid,$articleid,$type = 0) {
        $res = $this->book->getRelateBook(['sortid'=>$sortid],$articleid,$type);
        if($res) {
            foreach ($res as $key => $value) {
                $res[$key]['image'] = imageUrl($value['image']);
            }
            return $res;
        }
        return [];
    }

    public function makeReturn($data) {
        $res = [];
        foreach ($data as $key => $value) {
            $res[] = [
                'image' => $value['image'],
                'title' => $value['title'],
                'articleid' => $value['id'],
            ];
        }
        return $res;
    }

    /**
     * 增加评论 api/book/comment/add
     * 成功返回1 失败返回0+msg
     * @bodyParam articleid int required 书籍id
     * @bodyParam content string required 内容
     * @bodyParam grade int  评分  不是首次评分 可以不传 或者传0 都行
     * @bodyParam pid int 回复id  (给书评论传0)
     * @response {"code":1,"msg":"成功","data":{}}
     * @response {"code":0,"msg":"添加失败","data":{}}
     */
    public function addComment(Request $r) {
        $articleid = $r->input('articleid');
        $content = $r->input('content');
        $userid = getUserId($r->header('token'));
        $pid = $r->input('pid');
        $grade = $r->input('grade');
        if(!empty($grade)) {
            $isGrade = $this->book->getUserGrade($userid, $articleid);
            if(empty($isGrade)) {
                $this->book->addUserGrade($userid, $articleid, $grade);
            }

        }
        $data = [
            'articleid' => $articleid,
            'content' => $content,
            'userid' => $userid,
            'pid' => $pid,
            'created_at' => date('Y-m-d H:i:s', time()),
        ];
        $res = $this->book->addComment($data);
        if($res) apiReturn(1, '评论成功');
        apiReturn(0, '评论失败');
    }


    /**
     * 自动订阅 api/book/autoPaid
     * 成功返回1 失败返回0+msg
     * @bodyParam articleid int required 书籍id
     * @bodyParam type string required 类型(订阅和是取消) 1订阅 0取消
     * @response {"code":1,"msg":"success","data":{}}
     * @response {"code":0,"msg":"添加失败","data":{}}
     */
    public function autoPaid(Request $r) {
        $articleid = $r->input('articleid');
        $userid = getUserId($r->header('token'));
        $type = $r->input('type');
        if($type == 1) {
            $isAuto = $this->book->getAutoPaid($userid, $articleid);
            if(!empty($isAuto)) apiReturn(0, '已经自动订阅了');
            $res = $this->book->addAutoPaid($userid, $articleid);
        }else{
            $res = $this->book->delAutoPaid($userid, $articleid);
        }
        if($res) apiReturn(1, '自动订阅成功');
        apiReturn(0, 'error');
    }

     /**
     * 评论点赞 api/book/comment/like
     * 成功返回1 失败返回0+msg
     * @bodyParam pid int required 评论id
     * @bodyParam type string required 类型(订阅和是取消) 1点赞 0取消
     * @response {"code":1,"msg":"success","data":{}}
     * @response {"code":0,"msg":"添加失败","data":{}}
     */
    public function commentLike(Request $r) {
        $pid = $r->input('pid');
        $userid = getUserId($r->header('token'));
        $type = $r->input('type');
        if($type == 1) {
            $isLike = (new CommonModel('comment_like'))->getOne([
                'userid' => $userid,
                'pid'    => $pid,
            ]);
            if(!empty($isLike)) apiReturn(0, '已经点过赞了');
            $res = (new CommonModel('comment_like'))->addArray([
                'userid' => $userid,
                'pid'    => $pid,
                'created_at' => date('Y-m-d H:i:s', time()),
            ]);
        }else{
            $res = (new CommonModel('comment_like'))->delArray([
                'userid' => $userid,
                'pid'    => $pid,
            ]);
        }
        if($res) apiReturn(1, '点赞成功');
        apiReturn(0, '添加失败');
    }


    /**
     * 评论列表 api/book/comment/commentList
     * 成功返回1 失败返回0+msg
     * @bodyParam articleid int required 书id
     * @bodyParam page int required 页码 一页10条
     * @response {"code":1,"msg":"success","data":{"result":[{"userid":8,"pid":5,"content":"2","username":"\u7528\u62378","image":"","level":1,"like":0,"comment_num":0,"is_like":0,"create_time":"2019-06-03 03:50:57"},{"userid":1,"pid":3,"content":"\u554a\u54c8\u54c8 \u8bd5\u4e00\u8bd5","username":"\u5927\u54e5\u5927","image":"http:\/\/148.70.4.186\/user_logo\/1_lg.jpg","level":1,"like":0,"comment_num":0,"is_like":0,"create_time":"2019-05-29 09:04:41"},{"userid":1,"pid":1,"content":"\u54c8\u54c8\u54c8 \u8fd9\u53f7","username":"\u5927\u54e5\u5927","image":"http:\/\/148.70.4.186\/user_logo\/1_lg.jpg","level":1,"like":3,"comment_num":4,"is_like":0,"create_time":"2019-05-28 02:53:23"},{"userid":1,"pid":2,"content":"\u554a\u54c8\u54c8 \u8bd5\u4e00\u8bd5","username":"\u5927\u54e5\u5927","image":"http:\/\/148.70.4.186\/user_logo\/1_lg.jpg","level":1,"like":0,"comment_num":0,"is_like":0,"create_time":"2019-05-28 02:53:23"}]}}

     * @response {"code":0,"msg":"没有了","data":{}}
     */
    public function commentList(Request $r) {
        $articleid  = $r->input('articleid');
        $page = $r->input('page');
        $limit = 10;
        $offset = $limit * ($page - 1);
         //获取评论
        $comment = $this->book->getComment($articleid, $limit, $offset);
        $userid = '';
        if(!empty($r->header('token'))) $userid = getUserId($r->header('token'));
        $res = $this->getCommentUser($comment, $userid);
        if(empty($res)) return apiReturn(0,'没有了');
        return apiReturn(1, '成功', ['result'=>$res]);
    }


    /**
     * 评论详情 api/book/comment/info
     * 成功返回1 失败返回0+msg
     * @bodyParam pid int required 书id
     * @response{"code":1,"msg":"success","data":{"result":{"userid":1,"pid":1,"content":"\u54c8\u54c8\u54c8 \u8fd9\u53f7","username":"\u5927\u54e5\u5927","image":"http:\/\/148.70.4.186\/user_logo\/1_lg.jpg","level":1,"like":3,"comment_num":4,"is_like":0,"create_time":"2019-05-28 02:53:23","list":[{"userid":1,"pid":4,"content":"\u554a\u54c8\u54c8 \u8bd5\u4e00\u8bd5","username":"\u5927\u54e5\u5927","image":"http:\/\/148.70.4.186\/user_logo\/1_lg.jpg","level":1,"like":0,"comment_num":0,"is_like":0,"create_time":"2019-06-01 05:54:24"},{"userid":1,"pid":6,"content":"\u554a\u54c8\u54c8 \u8bd5\u4e00\u8bd5","username":"\u5927\u54e5\u5927","image":"http:\/\/148.70.4.186\/user_logo\/1_lg.jpg","level":1,"like":0,"comment_num":0,"is_like":0,"create_time":"2019-06-04 14:44:51"},{"userid":1,"pid":7,"content":"\u554a\u54c8\u54c8 \u8bd5\u4e00\u8bd5","username":"\u5927\u54e5\u5927","image":"http:\/\/148.70.4.186\/user_logo\/1_lg.jpg","level":1,"like":0,"comment_num":0,"is_like":0,"create_time":"2019-06-04 14:45:07"},{"userid":1,"pid":8,"content":"\u554a\u54c8\u54c8 \u8bd5\u4e00\u8bd5","username":"\u5927\u54e5\u5927","image":"http:\/\/148.70.4.186\/user_logo\/1_lg.jpg","level":1,"like":0,"comment_num":0,"is_like":0,"create_time":"2019-06-04 14:47:26"}]}}}
     * @response {"code":0,"msg":"没有了","data":{}}
     */
    public function commentInfo(Request $r) {
        $pid  = $r->input('pid');
        $page = $r->input('page');
        $comment[] = $this->book->getCommentByPid($pid);
        $userid = '';
        if(!empty($r->header('token'))) $userid = getUserId($r->header('token'));
        $data = $this->getCommentUser($comment, $userid);
        $list = $this->book->getCommentSon($pid);
        $son = [];
        if(!empty($list)) {
            $son = $this->getCommentUser($list);
        }
        $return = $data[0];
        $return['list'] = $son;
        if(empty($return)) return apiReturn(0,'没有了');
        return apiReturn(1, '成功', ['result'=>$return]);
    }


    public function getBookInfo(Request $r) {
        $articleid = $r->input('articleid');
        $userid = '';
        $articleInfo = $this->book->getBookInfo($articleid);
        if(empty($articleInfo)) apiReturn(0,'书籍不存在',[]);
        $data = [
            'title' => $articleInfo['title'],
            'articleid' => $articleInfo['id'],
            'author' => $articleInfo['author'],
            'image' => $articleInfo['image'],
        ];
        $sort = (new CommonModel('book_sort'))->getOne(['id'=>$articleInfo['sortid']]);
        $keywords = [
            $sort['sort'], ($articleInfo['finish'] == 1 ? '完结': '连载')
        ];
        $data['keywords'] = implode(",",$keywords);
        if(!empty($r->header('token'))) {
            $userid = getUserId($r->header('token'));
            $data['is_auto_paid'] = $this->book->getAutoPaid($userid, $articleid) ? 1 : 0;
            $shelf = (new CommonModel('user_favorite'))->getOne(['userid'=>$userid,'articleid'=>$articleid]);
            $data['is_shelf'] = $shelf ? 1 : 0;

        }else{
            $data['is_auto_paid'] = 0;
            $data['is_shelf'] = 0;
        }
        apiReturn(1,'成功',$data);

    }


    public function getCartoonContent(Request $r) {
        $bookid = $r->input('articleid');
        $chapterid = $r->input('chapterid');
        $chapter = $this->book->getChapter($bookid, $chapterid);
        $chapterInfo = $this->book->getChapterInfo($chapterid);
        //添加最近阅读
        if(!empty($r->header('token'))) {
            $userid = getUserid($r->header('token'));
            $this->book->addReadHistory($userid, $bookid, $chapterid);
            (new FavoriteModel())->updFavor(['articleid'=>$bookid,'userid'=>$userid],['last_read_time'=>date( "Y-m-d H:i:s", time())]);
        }
        if($chapterInfo['is_vip'] == 1) {
            $price = $chapterInfo['price'] ?? 49;
            $return = [
                'remain'    => 0,
                'price'     => $price,
                'chapterid' => $chapterInfo['id'],
                'subhead'   => $chapterInfo['subhead'],
                'content'   => [],
            ];
            //判断是否登录
            if(empty($r->header('token'))) {
                apiReturn(2, '暂未订阅', $return);
            }
            $userInfo = $this->user->getUserInfo(['id'=>$userid]);
            $return['remain'] = $userInfo['remain'];

            //检测是否订阅
            $isPaid = $this->book->checkPaid($userid, $chapterid);
            if(!$isPaid) {
                if($price > $userInfo['remain'])  apiReturn(2, '余额不足', $return);
                //检测是否自动订阅
                $isAutoPaid = $this->book->getAutoPaid($userid, $bookid);
                if($isAutoPaid) {
                    //自动订阅
                    $subject = "订阅". $chapterInfo['subhead'];

                    $res = $this->chapterPaid($userid, $price, $subject, $chapterInfo);
                    if(!$res) return apiReturn(0, '订阅失败');
                }else {
                    apiReturn(2, '暂未订阅', $return);
                }
            }
        }
        $chapter['content'] = str_replace('\r', "", $chapter['content']);
        $chapter['content'] = str_replace('\n', "", $chapter['content']);
        $content = json_decode($chapter['content'],true);
        foreach($content as $k=>$v) {
            if(empty($v)) unset($content[$k]);
        }
        $return = [
            'subhead' => $chapterInfo['subhead'],
            'content' => $content,
            'chapterid' => $chapter['id'],
        ];
        apiReturn( 1, '成功', $return);
    }


}
