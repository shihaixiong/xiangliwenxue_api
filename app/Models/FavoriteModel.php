<?php

namespace App\Models;

use App\Models\BaseModel as Models;
use DB;

class FavoriteModel extends Models
{
    protected $_dbName  = "book_app";
    protected $_dbTable = "user_favorite";
    
    public function getUserFavor($where = []) {
		$res = $this->getDb()->where($where)->orderBy('last_read_time', 'desc')->get();
		return json_decode(json_encode($res),true);
    }

    public function addFavor($data) {
        $res = $this->getDb()->insertGetId($data);
        return $res;
    }

    public function updFavor($where, $data) {
        $res = $this->getDb()->where($where)->update($data);
        return $res;
    }

    public function delFavor($userid, $articleid) {
        $res = $this->getDb()->whereIn('articleid', $articleid)->where('userid',$userid)->delete();
        return $res;
    }
    
}

