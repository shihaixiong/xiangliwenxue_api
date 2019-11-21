<?php

namespace App\Services;

use App\Models\AdminModel;
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
class BookStackService
{
	public function getBook($key='',$like='',$where='',$offset='',$limit='',$orderby=''){

		$select = ['id','title','desc','author','keywords','image','is_vip','word_count','sortid','finish'];

		if($like){
			$arr = (new AdminModel('books'))->getlike($key,$like,['visible'=>1],$select);
		}else{
			$arr = (new AdminModel('books'))->getOffset($where,$select,$offset,$limit,$orderby);
		}

		if(!$arr) return 0;

		foreach ($arr as $key => $value) {
			$arr[$key]['image'] = env('IMG_URL').$value['image'];
			$sort = (new AdminModel('book_sort'))->getOne(['id'=>$value['sortid']]);
            $keywords = [
                $sort['sort'], ($value['finish'] == 1 ? '完结': '连载')
            ];
            $arr[$key]['keywords'] = $keywords;
			$arr[$key]['articleid'] = $value['id'];
			unset($arr[$key]['id']);
			
		}
		return $arr;
	}
	//书籍分类导航
	public function getBookSort($channelid){

		$array = [];

		$arr2 = (new AdminModel('book_channel'))->getAll();
		if($arr2){
			$channel = [];
			foreach ($arr2 as $key => $value) {
				$channel['title'] = '频道';
				$channel['list'][0]['id']=0;
				$channel['list'][0]['name']='全部';
				$channel['list'][$key+1]['id'] = $value['id'];
				$channel['list'][$key+1]['name'] = $value['channel'];
			}
			$array[] = $channel;
		}

		$arr1 = (new AdminModel('book_sort'))->getAll();
		if($arr1){
			$sort = [];
			$sort['list'][0]['id']=0;
			$sort['list'][0]['name']='全部';
			$sort['title'] = '分类';
			$num = 1;
			foreach ($arr1 as $key => $value) {
				if($value['channel'] != $channelid &&  $channelid != 0) continue;
				$sort['list'][$num]['id'] = $value['id'];
				$sort['list'][$num]['name'] = $value['sort'];
				$num++;
			}
			$array[] = $sort;
		}

		

		$arr3 = [
			'title'=>'状态',
			'list' =>[
				0=>[
					'id'=>0,
					'name' =>'全部',
				],
				1=>[
					'id'=>1,
					'name' =>'完结',
				],
				2=>[
					'id'=>2,
					'name' =>'连载',
				]
			]
		];  
		$array[] = $arr3;
		
		return $array;
	}
}