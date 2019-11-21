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
 * 搜索
 */

class SearchBookController extends BaseController
{	

	protected $stacks;

    public function __construct(BookStackService $stacks) {
        $this->stacks = $stacks;
    }


	/**
     * 搜索 api/searchBook/search
     * 成功返回1 失败返回0+msg
     * @bodyParam search string required 搜索内容
     * @response {"code":1,"msg":"success","data":[]}
     * @response {"code":0,"msg":"密码设置失败","data":[]}
     */
	public function search(Request $r){
		$search = $r->search;
		
		if(!$search) apiReturn(0, '没找到内容',['result' => []]);
		

		$r->page ? $page = $r->page : $page = 1;
  		$pages = ((int)$page-1)*9;
		$search = wordMake2($search);
		$arr = $this->stacks->getBook('title',$search,'',$pages,20,'id');

		if(!$arr) apiReturn(0, '没找到内容',['result' => []]);


		$searchModel = new AdminModel('search_record');
		$res	 = $searchModel->getOne(['keywords'=>$search]);
		$return = [
			'result' => $arr
		];
		if($res){
			if($searchModel->editArray(['keywords'=>$search],['num'=>$res['num']+1])) apiReturn(1, '找到了', $return);
			apiReturn(0, '没找到内容', ['result' => []]);
		}else{
			if($searchModel->addArray(['keywords'=>$search,'num'=>1])) apiReturn(1, '找到了', $arr);
			apiReturn(0, '没找到内容', ['result' => []]);
		}

	}

	/**
     * 搜索记录 api/searchBook/searchList
     * 成功返回1 失败返回0+msg
     * @response {"code":1,"msg":"success","data":{"code":1,"msg":"success","data":[{"keyName":"b"},{"keyName":"d"},{"keyName":"1"}]}}
     * @response {"code":0,"msg":"没有搜索记录","data":[]}
     */
	public function searchList(Request $r){

		$arr=(new AdminModel('search_record'))->getOffset('','keywords',0,8,'num');
		if(!$arr) apiReturn(0, '没有搜索记录');
		
		$array = array_column($arr,'keywords');
		foreach ($array as $key => $value) {
			$return[] = [
				'keyName' => $value,
			];
		}

		apiReturn(1, '成功', $return);
	}
}
