<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use App\Models\AdminModel;
use App\Services\BookStackService;


/**
 * @group 书库
 */

class BookStackController extends BaseController
{	

	protected $stacks;

    public function __construct(BookStackService $stacks) {
        $this->stacks = $stacks;
    }

   /**
     * 书库 api/bookStack/list
     * 成功返回1 失败返回0+msg
     * @bodyParam page int 页码 
     * @response {"code":1,"msg":"success","data":[{"title":"\u674e\u83b2\u82b1\u591cya","desc":"\u4e00\u6bb5\u53ef\u6b4c\u53ef\u6ce3\u53ef\u7b11\u53ef\u7231\u7684\u8349\u6839\u5d1b\u8d77\u53f2","author":"\u674e\u83b2\u82b1","keywords":["\u5f02\u4e16"],"image":"http:\/\/127.0.0.1\/\/Users\/wangxin\/Desktop\/test_dir\/15583423582532.jpeg","is_vip":1,"articleid":9},{"title":"\u5c06\u591c","desc":"\u4e00\u6bb5\u53ef\u6b4c\u53ef\u6ce3\u53ef\u7b11\u53ef\u7231\u7684\u8349\u6839\u5d1b\u8d77\u53f2\u3002 \u3000\u3000\u4e00\u4e2a\u7269\u8d28\u8981\u6c42\u5b81\u6ee5\u52ff\u7f3a\u7684\u5f00\u6717\u5c11\u5e74\u884c\u3002 \u3000\u3000\u4e66\u9662\u540e\u5c71\u91cc\u6c38\u6052\u56de\u8361\u7740\u4ed6\u7591\u60d1\u7684\u58f0\u97f3\uff1a \u3000\u3000\u5b81\u53ef\u6c38\u52ab\u53d7\u6c89\u6ca6\uff0c\u4e0d\u4ece\u8bf8\u5723\u6c42\u89e3\u8131\uff1f \u3000\u3000\u4e0e\u5929\u6597\uff0c\u5176\u4e50\u65e0\u7a77\u3002 \u3000\u3000\u2026\u2026 \u3000\u3000\u2026\u2026 \u3000\u3000\u8fd9\u662f\u4e00\u4e2a\u201c\u522b\u4eba\u5bb6\u5b69\u5b50\u201d\u6495\u6389\u81c2\u4e0a\u6760\u7ae0\u540e\u7a7f\u8d8a\u524d\u5c18\u7684\u6545\u4e8b\uff0c\u4f5c\u8005\u4ffa\u8981\u8bf4\u7684\u662f\uff1a\u5343\u4e07\u5e74\u6765\uff0c\u62e5\u6709\u5403\u8089\u7684\u81ea\u7531\u548c\u81ea\u7531\u5403\u8089\u7684\u80fd\u529b\uff0c\u5c31\u662f\u6211\u4eec\u8fd9\u4e9b\u4e07\u7269\u4e4b\u7075\u594b\u6597\u7684\u76ee\u6807\u3002","author":"\u732b\u817b","keywords":["\u5f02\u4e16"],"image":"http:\/\/127.0.0.1\/book_pic\/18b45bbcaab3ac3d75cf6e250e5f3e66.jpeg","is_vip":1,"articleid":1}]}
     */
    public function list(Request $r){

    	$r->page ? $page = $r->page : $page = 1;
  		$pages = ((int)$page-1)*9;

  		$where['visible'] = 1;
  		$r->sortid ? $where['sortid'] = $r->sortid: 0;
  		// strlen($r->finish) ? $where['finish'] = $r->finish: 0;
      if($r->channel == 3) {
        $where['type'] = 2;
      } else{
        $r->channel ? $where['channel'] = $r->channel: 0;
      }
      if($r->finish)  $r->finish==1 ?  $where['finish'] = 1 : $where['finish'] = 0;
      //print_r($where);die;
  		$arr = $this->stacks->getBook('','',$where,$pages,20,'id');
  		if(!$arr) apiReturn(1, '无书籍',['result'=>[]]);
  		apiReturn(1, '成功', ['result'=>$arr]);

    }

   /**
     * 导航 api/bookStack/navigation
     * 成功返回1 失败返回0+msg
     * @response {"code":1,"msg":"success","data":{"channel":{"1":"\u7537\u9891","2":"\u5973\u9891","3":"\u6f2b\u753b"},"sort":{"1":"\u8a00\u60c5","2":"\u6821\u56ed","3":"\u7384\u5e7b","4":"\u6050\u6016","5":"\u60ac\u7591","6":"\u793e\u4f1a","7":"\u6218\u4e89","8":"\u81ea\u4f20"},"finish":["\u8fde\u8f7d\u4e2d","\u5df2\u5b8c\u7ed3"]}} 
     */
    public function navigation(Request $r){
      $channel = $r->input('channel') ?? 1;
    	$res = $this->stacks->getBookSort($channel);
    	if(!$res) apiReturn(0, '无分类');
    	apiReturn(1, '成功', $res);
    }

    /**
     * 排行榜 api/bookStack/rankingList
     * 成功返回1 失败返回0+msg
     * @response {"code":1,"msg":"success","data":{"channel":{"1":"\u7537\u9891","2":"\u5973\u9891","3":"\u6f2b\u753b"},"sort":{"1":"\u8a00\u60c5","2":"\u6821\u56ed","3":"\u7384\u5e7b","4":"\u6050\u6016","5":"\u60ac\u7591","6":"\u793e\u4f1a","7":"\u6218\u4e89","8":"\u81ea\u4f20"},"finish":["\u8fde\u8f7d\u4e2d","\u5df2\u5b8c\u7ed3"]}} 
     */
    public function rankingList(Request $r){

      $where['visible'] = 1;
      if($r->channel == 3) {
        $where['type'] = 2;
      } else{
        $r->channel ? $where['channel'] = $r->channel: 0;
      }
      
    	$res = $this->stacks->getBook('','',$where,0,10,'clicks');
    	if(!$res) apiReturn(0, '无排行榜',['result'=>[]]);

    	apiReturn(1, '成功', ['result'=>$res]);
    }
}