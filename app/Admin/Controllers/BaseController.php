<?php

namespace App\Admin\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB; 
use App\Models\AdminModel;

/** 
 * 公共
 */
class BaseController extends Controller
{

    /**
     *	文件上传
     *
     */
    public function uploadFiles($file,$dir){
        
        $filename = $file->getClientOriginalName();
        $type = strtolower(substr($filename,strrpos($filename,'.')+1));
        $newName = $dir.time().rand(1000, 9999).'.'.$type;
        // $file->move(base_path().'/public/'.$dir.'/',$newName );
        $file->move($dir,$newName );
        return ['oldname'=>$filename,'newname'=>$newName];
    }
}
