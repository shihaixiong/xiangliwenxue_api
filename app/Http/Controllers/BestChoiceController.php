<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use App\Models\FavoriteModel;
// use App\Providers\FuncServiceProvider;
use App\Services\BookService;
use App\Services\UserService;
use App\Services\RecommendService;
use Cache;

/**
 * @group 精选
 */
class BestChoiceController extends BaseController
{
    protected $book;
    protected $rec;
    protected $user;

    public function __construct(BookService $book,RecommendService $rec, UserService $user)
    {
        $this->book = $book;
        $this->rec  = $rec;
        $this->user = $user;
    }

    /**
     * 精选页接口 api/bestchoice/list
     * 成功返回1 失败返回0+msg
     * @bodyParam channel int required 频道 男频1 女频2 漫画3
     * @response {"code":1,"msg":"success","data":{"banner":[{"image":"http:\/\/127.0.0.1\/\/Users\/wangxin\/Desktop\/test_dir\/15583343512963.jpeg","link":"www.baidu.com\/aa","articleid":null}],"res_pos":[{"title":"\u6d4b\u8bd5\u63a8\u8350\u4f4d","type":1,"id":1,"content":[{"image":"http:\/\/127.0.0.1\/book_pic\/18b45bbcaab3ac3d75cf6e250e5f3e66.jpeg","id":1,"title":"\u5c06\u591c","desc":"简介","author":"\u732b\u817b","keywords":["\u5f02\u4e16"]}]},{"title":"\u6d4b\u8bd5\u63a8\u8350\u4f4d6","type":1,"id":2,"content":[{"image":"http:\/\/127.0.0.1\/book_pic\/18b45bbcaab3ac3d75cf6e250e5f3e66.jpeg","id":1,"title":"\u5c06\u591c","desc":"简介","author":"\u732b\u817b","keywords":["\u5f02\u4e16"]}]}]}}
     */
    public function list(Request $r) {
        //顶部banner
        $res = Cache::forget('ad');
        $channel = $r->input('channel') ?? 1;
        $cache = Cache::get('ad');
        if(!empty($cache)) {
            apiReturn(1,'成功',$cache);
        }else{
            $isvip = 0;
            if($r->header('token')) {
                $userid = getUserId($r->header('token'));
                $userInfo = $this->user->getUserInfo(['id'=>$userid]);
                $isvip = $userInfo['is_vip'];
            }
            $res = $this->getRecList($channel, $isvip);
            Cache::add('ad', $res, 60);
            apiReturn(1,'成功',$res);
        }
    }

    protected function getRecList($channel, $isvip=0) {
        $conn = ['image','id','title','desc','author','keywords','sortid','finish','word_count'];
        $return = [];
        //获取到推荐位类型
        $recType = $this->rec->getRecType($channel,1);
        foreach ($recType as $key => $value) {
            if($value['vip'] == 1 && $isvip == 0) continue;
            $rec = $this->rec->getRecommend($value['id']);
            //推荐位名称=顶部轮播图 设置为banner
            if($value['name'] == '顶部轮播图' && $value['channel'] == $channel) {
                $data = [];
                foreach ($rec as $k => $v) {
                    if(empty($v['image'])) continue;
                    $data[] = [
                        'image' => imageUrl($v['image']),
                        'link'  => $v['link'],
                        'articleid'  => $v['articleid'] ?? 0,
                    ];
                }
                $return['banner'] = $data;
                continue;

            }
            if($value['type'] == 1) {
                $res = $this->book->getBookInfo($rec, $conn);
                foreach ($res as $k => $v) {
                    if(empty($value)) continue;
                    $res[$k]['grade'] = number_format($this->book->getGrade($v['id']),1);
                    $res[$k]['articleid'] = $v['id'];
                    unset($res[$k]['id']);
                }
                if(!empty($res)) {
                    $return['res_pos'][] = [
                        'title'   => $value['name'],
                        'type'    => $value['style'],
                        'id'      => $value['id'],
                        'content' => $res,
                    ];
                }
            }else{
                $data = [];
                foreach ($rec as $k => $v) {
                    $data[] = [
                        'image' => imageUrl($v['image']),
                        'link'  => $v['link'],
                        'articleid'  => $v['articleid'] ?? 0,
                    ];
                }
                if(empty($data)) continue;
                $return['res_pos'][] = [
                        'title'   => $value['name'],
                        'type'    => 5,
                        'id'      => $value['id'],
                        'content' => $data,
                ];  

            }

        }
        return $return;
    }

    /**
     * 获取推荐位更多内容 api/bestchoice/list/more
     * 成功返回1 失败返回0+msg
     * @bodyParam id int required 推荐位id
     * @bodyParam page int required 页码
     * @response {"code":1,"msg":"success","data":{"result":[{"image":"http:\/\/148.70.4.186\/book_img\/15597287977431.jpg","id":482,"title":"\u6751\u957f\u7684\u540e\u9662","desc":"\u6751\u957f\u7684\u540e\u9662\u771f\u725b\u903c","author":"\u4e0d\u77e5\u9053","keywords":["\u540e\u9662","\u6751\u957f"]},{"image":"http:\/\/148.70.4.186\/book_img\/15597304604278.jpg","id":9,"title":"\u674e\u83b2\u82b1\u591cya","desc":"\u4e00\u6bb5\u53ef\u6b4c\u53ef\u6ce3\u53ef\u7b11\u53ef\u7231\u7684\u8349\u6839\u5d1b\u8d77\u53f2","author":"\u674e\u83b2\u82b1","keywords":["\u5f02\u4e16","\u91cd\u751f","\u590d\u4ec7"]},{"image":"http:\/\/148.70.4.186\/book_img\/15595504042952.jpg","id":1,"title":"\u5c06\u591c","desc":"\u4e00\u6bb5\u53ef\u6b4c\u53ef\u6ce3\u53ef\u7b11\u53ef\u7231\u7684\u8349\u6839\u5d1b\u8d77\u53f2\u3002 \u3000\u3000\u4e00\u4e2a\u7269\u8d28\u8981\u6c42\u5b81\u6ee5\u52ff\u7f3a\u7684\u5f00\u6717\u5c11\u5e74\u884c\u3002 \u3000\u3000\u4e66\u9662\u540e\u5c71\u91cc\u6c38\u6052\u56de\u8361\u7740\u4ed6\u7591\u60d1\u7684\u58f0\u97f3\uff1a \u3000\u3000\u5b81\u53ef\u6c38\u52ab\u53d7\u6c89\u6ca6\uff0c\u4e0d\u4ece\u8bf8\u5723\u6c42\u89e3\u8131\uff1f \u3000\u3000\u4e0e\u5929\u6597\uff0c\u5176\u4e50\u65e0\u7a77\u3002 \u3000\u3000\u2026\u2026 \u3000\u3000\u2026\u2026 \u3000\u3000\u8fd9\u662f\u4e00\u4e2a\u201c\u522b\u4eba\u5bb6\u5b69\u5b50\u201d\u6495\u6389\u81c2\u4e0a\u6760\u7ae0\u540e\u7a7f\u8d8a\u524d\u5c18\u7684\u6545\u4e8b\uff0c\u4f5c\u8005\u4ffa\u8981\u8bf4\u7684\u662f\uff1a\u5343\u4e07\u5e74\u6765\uff0c\u62e5\u6709\u5403\u8089\u7684\u81ea\u7531\u548c\u81ea\u7531\u5403\u8089\u7684\u80fd\u529b\uff0c\u5c31\u662f\u6211\u4eec\u8fd9\u4e9b\u4e07\u7269\u4e4b\u7075\u594b\u6597\u7684\u76ee\u6807\u3002","author":"\u732b\u817b","keywords":["\u5f02\u4e16"]},{"image":"http:\/\/148.70.4.186\/book_img\/15597305163073.jpg","id":483,"title":"\u519b\u5c11\u72ec\u5ba0\u60f9\u706b\u59bb","desc":"\u5389\u5bb3\u5462","author":"\u731c\u4e00\u731c","keywords":["\u60f9\u706b","\u72ec\u5ba0","\u5a07\u59bb"]}]}}
     */
    public function getList(Request $r) {
        $id = $r->input('id');
        $page = $r->input('page') ?? 1;
        $limit = 10;
        $offset = $limit * ($page - 1);
        $conn = ['image','id','title','desc','author','keywords','sortid','finish','word_count'];

        $list = $this->rec->getRecommend($id, $limit, $offset);
        if(empty($list)) apiReturn(0, '没有了');
        $res = $this->book->getBookInfo($list, $conn);

        apiReturn(1,'成功',['result'=>$res]);
    }
     /**
     * 获取频道接口 api/bestchoice/getChannel
     * 成功返回1 失败返回0+msg
     * @response {"code":1,"msg":"success","data":[{"channel_id":1,"channel_name":"\u7537\u9891"},{"channel_id":2,"channel_name":"\u5973\u9891"},{"channel_id":3,"channel_name":"\u6f2b\u753b"}]}
     */
    public function getChannel() {
        $data = [
                [
                    'channel_id'   => 1,
                    'channel_name' => '男频'
                ],[
                    'channel_id'   => 2,
                    'channel_name' => '女频'
                ],[
                    'channel_id'   => 3,
                    'channel_name' => '漫画'
                ]
        ];
        apiReturn(1,'成功',$data);
    }
}
