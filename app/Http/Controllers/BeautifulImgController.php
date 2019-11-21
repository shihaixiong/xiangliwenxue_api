<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use App\Services\BeautifulImgService;
use Cache;

/**
 * @group 美图
 */
class BeautifulImgController extends BaseController
{	
	
	protected $bimg;

    public function __construct(BeautifulImgService $bimg) {
        $this->bimg = $bimg;
    }

    
    /**
     * 美图列表 api/beautifulImg/list
     * 成功返回1 失败返回0+msg
     * @response {"code":1,"msg":"success","data":[{"id":3//图集id,"title":"\u6d4b\u8bd5\u8d44\u6599C"//图集标题,"desc":"\u4e00\u6bb5\u53ef\u6b4c\u53ef\u6ce3\u53ef\u7b11\u53ef\u7231\u7684\u8349\u6839\u5d1b\u8d77\u53f2\u3002 \u3000\u3000\u4e00\u4e2a\u7269\u8d28\u8981\u6c42\u5b81\u6ee5\u52ff\u7f3a\u7684\u5f00\u6717\u5c11\u5e74\u884c\u3002 \u3000\u3000\u4e66\u9662\u540e\u5c71\u91cc\u6c38\u6052\u56de\u8361\u7740\u4ed6\u7591\u60d1\u7684\u58f0\u97f3\uff1a \u3000\u3000\u5b81\u53ef\u6c38\u52ab\u53d7\u6c89\u6ca6\uff0c\u4e0d\u4ece\u8bf8\u5723\u6c42\u89e3\u8131\uff1f \u3000\u3000\u4e0e\u5929\u6597\uff0c\u5176\u4e50\u65e0\u7a77\u3002 \u3000\u3000\u2026\u2026 \u3000\u3000\u2026\u2026 \u3000\u3000\u8fd9\u662f\u4e00\u4e2a\u201c\u522b\u4eba\u5bb6\u5b69\u5b50\u201d\u6495\u6389\u81c2\u4e0a\u6760\u7ae0\u540e\u7a7f\u8d8a\u524d\u5c18\u7684\u6545\u4e8b\uff0c\u4f5c\u8005\u4ffa\u8981\u8bf4\u7684\u662f\uff1a\u5343\u4e07\u5e74\u6765\uff0c\u62e5\u6709\u5403\u8089\u7684\u81ea\u7531\u548c\u81ea\u7531\u5403\u8089\u7684\u80fd\u529b\uff0c\u5c31\u662f\u6211\u4eec\u8fd9\u4e9b\u4e07\u7269\u4e4b\u7075\u594b\u6597\u7684\u76ee\u6807\u3002"//描述,"cover":"http:\/\/127.0.0.1\/beautiful_img\/351bcaa82ab81504f9892ed613a91054.jpeg"//封面图,"praise":1//点赞数,"upload":1//下载数,"browse":11//浏览数,"is_praise":0//是否点赞},{"id":4,"title":"\u4e0a\u4f20\u5f15\u5bfc\u56fe","desc":"\u4e00\u6bb5\u53ef\u6b4c\u53ef\u6ce3\u53ef\u7b11\u53ef\u7231\u7684\u8349\u6839\u5d1b\u8d77\u53f2\u3002 \u3000\u3000\u4e00\u4e2a\u7269\u8d28\u8981\u6c42\u5b81\u6ee5\u52ff\u7f3a\u7684\u5f00\u6717\u5c11\u5e74\u884c\u3002 \u3000\u3000\u4e66\u9662\u540e\u5c71\u91cc\u6c38\u6052\u56de\u8361\u7740\u4ed6\u7591\u60d1\u7684\u58f0\u97f3\uff1a \u3000\u3000\u5b81\u53ef\u6c38\u52ab\u53d7\u6c89\u6ca6\uff0c\u4e0d\u4ece\u8bf8\u5723\u6c42\u89e3\u8131\uff1f \u3000\u3000\u4e0e\u5929\u6597\uff0c\u5176\u4e50\u65e0\u7a77\u3002 \u3000\u3000\u2026\u2026 \u3000\u3000\u2026\u2026 \u3000\u3000\u8fd9\u662f\u4e00\u4e2a\u201c\u522b\u4eba\u5bb6\u5b69\u5b50\u201d\u6495\u6389\u81c2\u4e0a\u6760\u7ae0\u540e\u7a7f\u8d8a\u524d\u5c18\u7684\u6545\u4e8b\uff0c\u4f5c\u8005\u4ffa\u8981\u8bf4\u7684\u662f\uff1a\u5343\u4e07\u5e74\u6765\uff0c\u62e5\u6709\u5403\u8089\u7684\u81ea\u7531\u548c\u81ea\u7531\u5403\u8089\u7684\u80fd\u529b\uff0c\u5c31\u662f\u6211\u4eec\u8fd9\u4e9b\u4e07\u7269\u4e4b\u7075\u594b\u6597\u7684\u76ee\u6807\u3002","cover":"http:\/\/127.0.0.1\/beautiful_img\/fd263a87985c96b30b330b5c5d0d6615.jpeg","praise":1,"upload":0,"browse":0,"is_praise":0}]}
     */
    public function list(Request $r){

        $r->page ? $page = $r->page : $page = 1;
        $limit = 20;
        $pages = ((int)$page-1)*$limit;
        $where = [];
        $sort = $r->sort;
        if($sort) $where['sort'] = $sort;
    	$uid = '';
    	if(!empty($r->header('token'))) {
            $uid = getUserid($r->header('token'));
        }

    	$arr = $this->bimg->getBimgAll($where,[
    		'id','title','desc','cover','praise','upload','browse'
    	],$uid,$pages,$limit,'id');
        if(!$arr) apiReturn(0, '暂时没有图集');
        foreach ($arr as $k=>$v) {
            if($v['desc'] == null) $arr[$k]['desc'] = '';
        }
    	apiReturn(1, 'success', ['result'=>$arr]);
    	

    }

    /**
     * 美图详情 api/beautifulImg/details
     * 成功返回1 失败返回0+msg
     * @bodyParam id int required 图集id
     * @response {"code":1,"msg":"success","data":{"id":3,"image":["http:\/\/127.0.0.1\/beautiful_img\/6ae0874044eca7a5cd3df3603c0ce2e7.jpeg","http:\/\/127.0.0.1\/beautiful_img\/64a6adef6f7d5b77a3d91817559b8e5b.jpeg","http:\/\/127.0.0.1\/beautiful_img\/039d8d99699b6e4868ce55ab1f3afc5d.jpg"],"praise":1,"upload":1,"browse":10,"is_praise":0}}
     */
    public function details(Request $r){
    	$id = $r->id;
    	if(!$id) apiReturn( 0, '缺少参数');

    	$uid = '';
    	if(!empty($r->header('token'))) {
            $uid = getUserid($r->header('token'));
        }
    	
    	$arr = $this->bimg->getBimgOne(['id'=>$id],[
    		'id','image','praise','upload','browse'
    	],$uid);

    	if(!$arr) apiReturn(0, '此图集id没有图');

    	//浏览量+1
    	if(!($this->bimg->addBimgNum($id,'browse')))  apiReturn( 0, '修改浏览量失败');
    	apiReturn(1, 'success', $arr);
    	
    }

     /**
     * 点赞 api/beautifulImg/praise
     * 成功返回1 失败返回0+msg
     * @bodyParam id int required 图集id
     * @response {"code":1,"msg":"success","data":{}}
     */
    public function praise(Request $r){
    	$id  = $r->id;
    	if(!$id) apiReturn( 0, '缺少参数');
    	
        $uid = '';
    	if(!empty($r->header('token'))) {
            $uid = getUserid($r->header('token'));
        }
    	
    	if(!($this->bimg->addBimgNum($id,'praise',$uid))) apiReturn( 0, '修改下载量失败');
    	apiReturn(1, 'success');
    }

     /**
     * 下载 api/beautifulImg/upload
     * 成功返回1 失败返回0+msg
     * @bodyParam id int required 图集id
     * @response {"code":1,"msg":"success","data":{}}
     */
    public function upload(Request $r){
    	$id = $r->id;
    	if(!$id) apiReturn( 0, '缺少参数');
    	if(!($this->bimg->addBimgNum($id,'upload'))) apiReturn( 0, '修改下载量失败');
    	apiReturn(1, 'success');
    }

    public function getSort() {
        $sort = $this->bimg->getSort();    
        apiReturn(1, 'success', $sort);
    }



}