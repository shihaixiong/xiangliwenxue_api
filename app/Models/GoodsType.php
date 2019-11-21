<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoodsType extends Model
{
    
    protected $table = "goods_type";//要连接的表名称

    public function setImgAttribute($img)
	{
	    if (is_array($img)) {
	        $this->attributes['img'] = json_encode($img);
	    }
	}

	public function getImgAttribute($img)
	{
	    return json_decode($img, true);
	}


}
