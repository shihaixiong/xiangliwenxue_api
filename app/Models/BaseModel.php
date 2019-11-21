<?php

namespace App\Models;

use DB;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query;

class BaseModel extends Model
{
    public $timestamps = false;

    //platform区分平台 如果想连2cloo传 _2cloo
    public function __construct() {
        //$this->_db = DB::connection($this->_dbName);
        //$this->_dbTable = 'books';
    }
    /*
    ┊* 获取DB对象
    ┊* @return  object DB
    ┊*/
    public function getDb() { 
        

       if(!empty($this->_dbTable))  return  DB::connection($this->_dbName)->table($this->_dbTable);
       return $this->_db;
    }
}
