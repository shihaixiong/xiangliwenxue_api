<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BeautifulImg extends Model
{
    
    protected $table = "beautiful_img";//要连接的表名称

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
	
	public function setCoverAttribute($cover)
	{
	    if (is_array($cover)) {
	        $this->attributes['cover'] = json_encode($cover);
	    }

	}

	public function getCoverAttribute($cover)
	{
	    return json_decode($cover, true);
	}


}
