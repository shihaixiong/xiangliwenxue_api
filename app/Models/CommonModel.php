<?php
namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\BaseModel;

class CommonModel extends BaseModel
{

    protected $_dbName  = "book_app";
    protected $_dbTable ;

    public function __construct($tables) {
        $this->_dbTable = $tables;
    }

    //查询所有
    public function getAll($where='',$select='',$orderby='', $seq = 'asc'){
        $model=$this->getDb();
        if(!empty($select)) $model->select($select);
        if(!empty($where)) $model->where($where);
        if(!empty($orderby)) $model->orderby($orderby,$seq);
        return json_decode(json_encode($model->get()), true);
    }
    //查询单条
    public function getOne($where='',$select=''){
        $model=$this->getDb();
        if(!empty($select)) $model->select($select);
        if(!empty($where)) $model->where($where);
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
        return $model->count();;
    }

     //查询分页排序
    public function getOffset($where='',$select='',$limit='',$orderby='', $seq='asc', $offset = 0){
        $model=$this->getDb();
        if(!empty($select)) $model->select($select);
        if(!empty($where)) $model->where($where);
        if(!empty($orderby)) $model->orderby($orderby,$seq);
        if(!empty($limit)) $model->limit($limit);
        if(!empty($offset)) $model->offset($offset);

        return json_decode(json_encode($model->get()), true);
    }
    //
    public function addGetId($arr){
        return $this->getDb()->insertGetId($arr);
    }
}