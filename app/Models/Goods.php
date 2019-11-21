<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Goods extends Model
{
    
    protected $table = "goods";//要连接的表名称

    public function setImageAttribute($image)
	{
	    if (is_array($image)) {
	        $this->attributes['image'] = json_encode($image);
	    }
	}

	public function getImageAttribute($image)
	{
	    return json_decode($image, true);
	}



}
