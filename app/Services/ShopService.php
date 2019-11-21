<?php

namespace App\Services;

use App\Models\AdminModel;
use Illuminate\Support\Facades\DB;
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
class ShopService
{
	public function getGoodsList($uid=''){
		// $goodsModel = new AdminModel('goods');
		
		// $typeArr 	= $goodsModel->getGroup('','type','type');
		
		// $array = [];
		// foreach ($typeArr as $key => $value) {
		// 	$res = DB::select("select g.id,name,t.type,cover,price from goods as g left join goods_type as t on g.type = t.id where status = 1 and g.type = ".$value['type']."  order by g.created_at desc limit ".$num);
		// 	if(!$res) return 0;
		// 	$res_ = json_decode(json_encode($res), true);

		// 	foreach ($res_ as $k => $v) {
		// 		$res_[$k]['cover'] = env('IMG_URL').$v['cover'];
				
		// 	}

		// 	$array[$res_[0]['type']] = $res_; 
		// }


		$arr = (new AdminModel('goods'))->getLeft('goods_type',['goods.type'=>'goods_type.id'],['status'=>1],['goods.id','name','cover','goods_type.type','price','sold']);
		if(!$arr) return 0;

		$result['integral'] = 0;
		if($uid) {
			$integral = (new AdminModel('user'))->getOne(['id'=>$uid],'integral');
			if($integral) $result['integral'] = $integral['integral'];
		}

		$array=[];
		foreach( $arr as $key=>$val){
			$array[$val['type']]['goods_type'] =$val['type'];  
			$val['cover'] =env('IMG_URL').'/upload/'.$val['cover'];
			$array[$val['type']]['goods'][]=$val;  
		}

		sort($array);

		$result['goods'] = $array;
		return $result;
	}
	
	public function getGoodsOne($id){
		$arr = (new AdminModel('goods'))->getOne(['id'=>$id],['id','image','desc']);
		if(!$arr) return 0;

		$arr['image'] = json_decode($arr['image'],true);

		for ($i=0; $i < count($arr['image']); $i++) { 
			$arr['image'][$i] = env('IMG_URL').'/upload/'.$arr['image'][$i];
		}

		return $arr;
	}

	public function integralOrderList($uid){
		$arr = (new AdminModel('integral_order'))->getLeft('goods',['integral_order.goods_id'=>'goods.id'],['integral_order.uid'=>$uid],['goods.id','goods.name','cover','goods.price','integral_order.order_num','integral_order.order_status','integral_order.express_num','integral_order.created_at']);
		if(!$arr) return 0;
		foreach ($arr as $key => $value) {
			$arr[$key]['cover'] = env('IMG_URL').'/upload/'.$value['cover'];
			if(!$arr[$key]['express_num']) $arr[$key]['express_num']='';
			$arr[$key]['created_at'] = date('Y-m-d',strtotime($value['created_at']));
		}
		return $arr;
	}
}