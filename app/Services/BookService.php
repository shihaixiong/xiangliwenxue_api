<?php

namespace App\Services;

use App\Models\CommonModel;
use DB;
use Cache;
use App\Services\TaskService;
/**
 * Class EmailService
 *
 * @package \App\Services
 */
/**
 * Class EmailService
 *
 * @package App\Services
 */
class BookService
{
    protected $task;
    public function __construct(TaskService $task) {
        $this->task = $task;
    }
    public function getBookInfo($id, $conn = ['*']) {
        if(is_array($id)) {
            //如果id 是数组 批量查询
            $res = [];
            foreach ($id as $key => $value) {
                if(empty($value['articleid'])) continue;
                $book = (new CommonModel('books'))->getOne(['id'=>$value['articleid']], $conn);
                if(empty($book)) continue;
                $book['image'] = imageUrl($book['image'], 'book');
                // $book['keywords'] = explode(',',$book['keywords']);
                $sort = (new CommonModel('book_sort'))->getOne(['id'=>$book['sortid']]);
                $keywords = [
                    $sort['sort'], ($book['finish'] == 1 ? '完结': '连载')
                ];
                $book['keywords'] = $keywords;

                $res[] = $book;
            }
            return $res;
        } else {
            $book = (new CommonModel('books'))->getOne(['id'=>$id]);
            if(empty($book)) return false;
            $book['image'] = imageUrl($book['image']);
            $res = $book;
            return $res;
        }
    }

    public function getChapters($articleid) {
        $list = (new CommonModel('chapters'))->getAll(['book_id'=>$articleid, 'visible'=>1], ['subhead','id','is_vip','word_count'], 'displayorder', 'asc');

        if($list) {
            $return = [];
            foreach ($list as $key => $value) {
                $value['chapterid'] = $value['id'];
                unset($value['id']);
                $return[] = $value;
            }
            return $return;
        } 
        return false;
    }

    public function getChapter($articleid, $chapterid) {
        $content = (new CommonModel('chapter_'.($chapterid % 100)))->getOne(['id'=>$chapterid]);
        if($content) {
            return $content;
        } 
        return false;
    }

    public function getChapterInfo($chapterid) {
        $content = (new CommonModel('chapters'))->getOne(['id'=>$chapterid]);
        return $content;
    }

    public function checkPaid( $userid, $chapterid) {
        $isPaid = (new CommonModel('paid'))->getOne(['userid'=>$userid, 'chapterid'=>$chapterid]);
        if($isPaid) return true;
        return false;
    }

    public function getUserArticlePaid($userid, $articleid) {
        $list = (new CommonModel('paid'))->getAll(['userid'=>$userid, 'articleid'=>$articleid]);
        if($list) return $list;
        return false;
    }

    public function paid($userid, $chapterid, $articleid) {
        $data = [
            'userid' => $userid,
            'chapterid' => $chapterid,
            'created_at' => date('Y-m-d H:i:s', time()),
            'articleid' => $articleid,
        ];
        $res = (new CommonModel('paid'))->addArray($data);
        $this->task->addTaskLog($userid, 4);

        if($res) return true;
        return false;
    }
 
    public function addReadHistory($userid, $articleid, $chapterid) {
        $where = [
            'userid' => $userid,
            'articleid' => $articleid,
        ];
        $res = (new CommonModel('read_history'))->getOne($where);
        if($res) {
            $data = [
                'chapterid' => $chapterid, 
                'last_read_time' => date('Y-m-d H:i:s', time()),
            ];
            $res = (new CommonModel('read_history'))->editArray($where, $data);
        }else{
            $data = [
                'chapterid'  => $chapterid, 
                'userid'     => $userid, 
                'articleid'  => $articleid, 
                'last_read_time' => date('Y-m-d H:i:s', time()),
            ];
            $res = (new CommonModel('read_history'))->addArray($data);
        }
    }

    public function getLastReadOne($userid) {
        $where = [
            'userid' => $userid,
        ];
        $res = (new CommonModel('read_history'))->getOffset($where,'',1,'last_read_time','desc');
        if(!empty($res)) return $res[0];
        return false;
    }

    public function getChapterCount($articleid) {
        $count = (new CommonModel('chapters'))->getCount(['book_id'=>$articleid]);
        if($count) return $count;
        return 0;
    }

    public function getRandBook($where = []){
        $res = (new CommonModel('books'))->getDb();
        if(!empty($where)) $res->where($where);
        $res = $res->where('visible',1)->orderBy(DB::raw('RAND()'))->take(4)->get();
        if($res) return json_decode(json_encode($res),true);
        return $res;
    }

    public function getGrade($articleid) {
        $grade = Cache::get('grade_'.$articleid);
        if($grade > 0) return $grade;
        $res = (new CommonModel('book_grade'))->getDb()->where('articleid',$articleid)->groupBy('articleid')->avg('grade');
        Cache::add('grade_'.$articleid, $res, 300);
        if($res) return $res;
        return 0;
    }

    public function getUserGrade($userid, $articleid) {
        $res = (new CommonModel('book_grade'))->getDb()->where(['articleid'=>$articleid,'userid'=>$userid])->first();
        if($res) return $res->grade;
        return 0;
    }

    public function addUserGrade($userid, $articleid, $grade) {
        $res = (new CommonModel('book_grade'))->getDb()->insert([
            'userid'     => $userid,
            'articleid'  => $articleid,
            'grade'      => $grade,
            'created_at' => date( "Y-m-d H:i:s", time())
        ]);
        if($res) return true;
        return false;
    }
     public function getGradeNum($articleid) {
        $res = (new CommonModel('book_grade'))->getDb()->where('articleid',$articleid)->count();
        if($res) return $res;
        return 0;
    }

    public function getComment($articleid, $limit, $offset = 0) {
        $res = (new CommonModel('comment'))->getOffset(['articleid'=>$articleid,'pid'=>0],'',$limit,'created_at', 'desc', $offset);
        if($res) return $res;
        return [];
    }

    public function addComment($data) {
        $res = (new CommonModel('comment'))->addArray($data);
        if($res) return $res;
        return false;
    }

    public function getCommentByPid($pid) {
        $res = (new CommonModel('comment'))->getOne(['id'=>$pid]);
        if($res) return $res;
        return [];
    }

    public function getCommentSon($pid) {
        $res = (new CommonModel('comment'))->getAll(['pid'=>$pid]);
        if($res) return $res;
        return [];
    }
    public function getRelateBook($where, $articleid, $type=0){
        $res = (new CommonModel('books'))->getDb();
        if(!empty($where)) $res->where($where);
        if(!empty($type)) $res->where('type',$type);
        $res->where('id','!=',$articleid);
        $res = $res->where('visible',1)->orderBy('clicks','desc')->take(4)->get();
        if($res) return json_decode(json_encode($res),true);
        return $res;
    }
    /**
     * 添加自动订阅
     * @author yangguang
     * @date   2019-06-01T13:18:27+0800
     * @param  [type]                   $userid     [description]
     * @param  [type]                   $articleid [description]
     */
    public function addAutoPaid($userid, $articleid) {
        $res = (new CommonModel('auto_paid'))->addArray(['userid'=>$userid,'articleid'=>$articleid,'created_at'=>date('Y-m-d H:i:s',time())]);
        return $res;
    }
    /**
     * 查询自动订阅
     * @author yangguang
     * @date   2019-06-01T13:18:41+0800
     * @param  [type]                   $userid     [description]
     * @param  [type]                   $articleid [description]
     */
    public function getAutoPaid($userid, $articleid) {
        $res = (new CommonModel('auto_paid'))->getOne(['userid'=>$userid,'articleid'=>$articleid]);
        return $res;
    }
    /**
     * 删除自动订阅
     * @author yangguang
     * @date   2019-06-01T13:19:01+0800
     * @param  [type]                   $userid     [description]
     * @param  [type]                   $articleid [description]
     */
    public function delAutoPaid($userid, $articleid) {
        $res = (new CommonModel('auto_paid'))->delArray(['userid'=>$userid,'articleid'=>$articleid]);
        return $res;
    }

    public function getUserPaid($userid, $limit = 10, $offset = 0) {
        $res = (new CommonModel('paid'))->getDb()->select('articleid')->where('userid', $userid)->groupBy('articleid');
        $res->limit($limit);
        $res->offset($offset);
        $res = $res->get();
        if($res) return json_decode(json_encode($res), true);
        return false;
    }

    public function getLastRead($userid, $limit = 10, $offset = 0) {
        $res = (new CommonModel('read_history'))->getDb()->select(['articleid'])->where('userid', $userid);
        $res->groupBy('articleid');
        $res->orderBy('last_read_time', 'desc');
        $res->offset($offset);
        $res->limit($limit);
        $res = $res->get();
        if($res) return json_decode(json_encode($res), true);
        return false;
    }

}
