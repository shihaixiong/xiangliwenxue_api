<?php
namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\BaseModel;

class AdminModel extends BaseModel
{

	protected $_dbName  = "book_app";
    protected $_dbTable ;

    public function __construct($tables) {
        $this->_dbTable =$tables;
    }

    //查询所有
	public function getAll($where='',$select='',$orderby=''){
		$model=$this->getDb();
		if(!empty($select)) $model->select($select);
		if(!empty($where)) $model->where($where);
		if(!empty($orderby)) $model->orderby($orderby,'desc');
		return json_decode(json_encode($model->get()), true);
	}
	//查询单条
	public function getOne($where='',$select='',$orderby=''){
		$model=$this->getDb();
		if(!empty($select)) $model->select($select);
		if(!empty($where)) $model->where($where);
		if(!empty($orderby)) $model->orderby($orderby,'desc');
		return json_decode(json_encode($model->first()), true);
	}
	//添加
	public function addArray($arr){
		return $this->getDb()->insert($arr);
	}
	//修改
	public function editArray($where,$arr){
		return $this->getDb()->where($where)->update($arr);
	}
	//删除
	public function delArray($where){
		return $this->getDb()->where($where)->delete();
	}
	//whereIn
	public function getIn($field,$whereArray,$select=''){
		$model=$this->getDb();
		if(!empty($select)) $model->select($select)->whereIn($field,$whereArray);;
		return json_decode(json_encode($model->get()), true);
	}
	public function getCount($where=''){
		$model=$this->getDb();
		if(!empty($where)) $model->where($where);
		return $model->count();
	}

	 //查询分页排序
	public function getOffset($where='',$select='',$offset='',$limit='',$orderby=''){
		$model=$this->getDb();
		if(!empty($select)) $model->select($select);
		if(!empty($where)) $model->where($where);
		if(!empty($orderby)) $model->orderby($orderby,'desc');
		// ->orderby('id','desc');
		if(!empty($limit)) $model->offset($offset)->limit($limit);
		return json_decode(json_encode($model->get()), true);
	}
	//
	public function addGetId($arr){
		return $this->getDb()->insertGetId($arr);
	}

	public function getlike($key,$like,$where='',$select=''){
		$model = $this->getDb();
		if(!empty($select)) $model->select($select);
		if(!empty($where)) $model->where($where);
		if(!empty($like))	$model->where($key,'like','%'.$like.'%');
		return json_decode(json_encode($model->get()), true);
	}

	//分组
	public function getGroup($where='',$select='',$group){
		$model = $this->getDb();
		if(!empty($select)) $model->select($select);
		if(!empty($where)) $model->where($where);
		return json_decode(json_encode($model->groupBy($group)->get()), true);
	}

	public function getLeft($tab,$on,$where='',$select=''){
		$model = $this->getDb();
		if(!empty($select)) $model->select($select);
		if(!empty($where)) $model->where($where);
		$model->join($tab,$on);
		return json_decode(json_encode($model->get()), true);
	}

	public function getSum($sum,$where=''){
		$model = $this->getDb();
		if(!empty($where)) $model->where($where);
		return $model->sum($sum);
	}
}