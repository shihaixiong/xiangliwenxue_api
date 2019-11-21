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
class BeautifulImgService
{
    public function getBimgAll($where='',$select='',$uid='',$offset='',$limit='',$orderby=''){

        $arr = (new AdminModel('beautiful_img'))->getOffset($where,$select,$offset,$limit,$orderby);
        if(!$arr) return 0;

        $bipArr = [];

        if($uid) {
            $bip = (new AdminModel('beautiful_img_praise'))->getAll(['uid'=>$uid]);
            if($bip)   $bipArr = array_column($bip,'bid');
            
        }
        foreach ($arr as $key => $value) {
            $cover = json_decode($value['cover'],true);
            foreach ($cover as $k=>$v) {
                $cover[$k] = env('IMG_URL').'/upload/'.$v;
                
            }
            $arr[$key]['cover'] = $cover;
            $arr[$key]['is_praise'] = 0;

            if($uid && (!empty($bipArr))){
                
                if(in_array($value['id'], $bipArr)) $arr[$key]['is_praise'] = 1;
            }

        }
        
        return $arr;
    }

    public function getBimgOne($where='',$select='',$uid=''){

        $arr = (new AdminModel('beautiful_img'))->getOne($where,$select);
        if(!$arr) return 0;

        $img = json_decode($arr['image'],true);

        for($i=0; $i<count($img); $i++){
            $img[$i] =  env('IMG_URL').'/upload/'.$img[$i];
        }

        $arr['image']     = $img;
        $arr['is_praise'] = 0;

        if($uid){
            if((new AdminModel('beautiful_img_praise'))->getOne(['uid'=>$uid,'bid'=>$arr['id']])) $arr['is_praise'] = 1;
        }
        
        return $arr;
    }
    //浏览量+下载量+点赞+
    public function addBimgNum($id,$type,$uid=''){

        $bimgModel = new AdminModel('beautiful_img');
        $arr = $bimgModel->getOne(['id'=>$id],$type);
        if(!$arr) return 0;

        if($type == "praise"){
            $bimgPraiseModel = new AdminModel('beautiful_img_praise');

            if($bimgPraiseModel ->getOne(['uid'=>$uid,'bid'=>$id])) return 1;

            $res = $bimgPraiseModel ->addArray([
                'uid'       =>  $uid,
                'bid'       =>  $id,
                'created_at'=>  date('Y-m-d h:i:s',time()),
            ]);
            if(!$res) return 0;
            
            
        }

        if($bimgModel->editArray(['id'=>$id],[$type=>$arr[$type]+1])) return 1;

        return 0;
    }

    public function getSort() {
        $res = (new AdminModel('img_sort'))->getAll();
        return $res;
    }
}
