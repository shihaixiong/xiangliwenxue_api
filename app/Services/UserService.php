<?php

namespace App\Services;

use Cache;
use App\Models\CommonModel;
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
class UserService
{
    public function login($userid, $oldToken) {
        $token = md5(time().$userid);
        if(!empty($oldToken)) Cache::forget($oldToken);
        Cache::add($token, $userid);
        $data = [
            'last_login_time' => date('Y-m-d H:i:s',time()),
            'token' => $token,
        ];
        (new CommonModel('user'))->editArray(['id'=> $userid], $data);
        return $token;
    }

    public function getUserInfo ($where) {
        $res = (new CommonModel("user"))->getOne($where);
        if(!empty($res)) {
            return $res;
        }else{
            return false;
        }
    }

    public function regiset($data) {
        $time = date("Y-m-d H:i:s",time());
        $data = [
            'phone'    => $data['phone'],
            'created_at' => $time,
            'updated_at' => $time,
        ];
        $userid = (new CommonModel('user'))->addGetId($data);
        return $userid;
    }

    public function loginByCode($where) {
        //code登录需要判断当前手机号是否注册过  没有注册 先执行注册
        $user = $this->getUserInfo($where);
        if($user) {
            $token = $this->login($user['id'], $user['token']);
            return ['token'=>$token,'userid'=>$user['id']];
        }else {
            $userid = $this->regiset($where);
            $upd    = (new CommonModel('user'))->editArray(['id'=> $userid], ['username'=>"用户$userid"]);
            $token  = $this->login($userid, '');
            return ['token'=>$token,'userid'=>$userid];
        }
    }

    public function loginByPwd($where) {
        $where['password'] = md5($where['password']);
        $user = $this->getUserInfo($where);
        if($user) {
            $token = $this->login($user['id'], $user['token']);
            return $token;
        }else {
            return false;
        }
    }

    public function updPwd($userid, $password) {
        $data = [
            'password' => $password,
            'updated_at' => date("Y-m-d H:i:s", time())
        ];
        $upd = (new CommonModel('user'))->editArray(['id'=> $userid], $data);
        if($upd) return true;
        return false;


    }

    public function updUserInfo($userid, $data) {
        $upd = (new CommonModel('user'))->editArray(['id'=> $userid], $data);
        if($upd) return true;
        return false;


    }

    public function checkIsNew($userid) {
        $where = ['id'=>$userid,'password'=>'-'];
        $user = $this->getUserInfo($where);
        if($user) return 1;
        return 0;
    }

    public function wechatLogin($data) {
        $isset = (new CommonModel('wechat_login'))->getOne(['openid'=>$data['openid']]);
        if(empty($isset)) {
            //注册一个用户
            $openid = $data['openid'];
            unset($data['openid']);
            $userid = $this->addUser($data);
            $image = $this->getImg($data['image'], $userid);
            $this->updUserInfo($userid,['image'=>$image]);
            $res = (new CommonModel('wechat_login'))->addGetId(['userid'=>$userid, 'openid'=>$openid, 'created_at'=>date( "Y-m-d H:i:s", time())]);
            if($res) $token  = $this->login($userid, '');
            return ['token'=>$token,'isNew'=>1];
        } else{
            $userid = $isset['userid'];
            $userInfo = $this->getUserInfo(['id'=>$userid]);
            $token = $userInfo['token'];
            $token  = $this->login($userid, $token);
            return ['token'=>$token,'isNew'=>0];
        }
    }

    public function getImg($image, $userid) {
        $name = 'user_logo/'.$userid."_lg.jpg";
        //下载用户头像
        $img = curl($image);
        if(empty($img)) return false;
        $fp2  = fopen($name , "a");
        fwrite($fp2, $img);
        fclose($fp2);
        return $name;
    }
    function dlfile($file_url, $save_to) {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_POST, 0); 

        curl_setopt($ch,CURLOPT_URL,$file_url); 

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

        $file_content = curl_exec($ch);

        curl_close($ch);

        $downloaded_file = fopen($save_to, 'w');

        fwrite($downloaded_file, $file_content);

        fclose($downloaded_file);

    }
    public function addUser($data) {
        $time = date("Y-m-d H:i:s",time());
        $data['created_at'] = $time;
        $data['updated_at'] = $time;
        $userid = (new CommonModel('user'))->addGetId($data);
        return $userid;
    }

    public function inviteCode($userid){
        if(empty($userid)) return '';
        $userid = $userid;
        return $this->makeCode($userid, 1);
        // return $this->convBase($userid,'0123456789','0123456789ABCDEFGHJKLMNPQRSTUVWXYZ');
    }

    public function unInviteCode($code){
        if(empty($code)) return 0;
        return $this->makeCode($code, 2);

        // $res = $this->convBase($code,'0123456789ABCDEFGHJKLMNPQRSTUVWXYZ','0123456789') - 70000000;
        return $res;
    }

    public function makeCode($userid, $type=1) {
        if($type == 1) {
            $code = 1000000+$userid;
            $code = (string) $code;
            $code = substr($code,1);
            return $code;
        } else {
            $code = (int) $userid;
            return $code;
        }
    }

    public function convBase($numberInput, $fromBaseInput, $toBaseInput)
    {
        if ($fromBaseInput==$toBaseInput) return $numberInput;
        $fromBase = str_split($fromBaseInput,1);
        $toBase = str_split($toBaseInput,1);
        $number = str_split($numberInput,1);
        $fromLen=strlen($fromBaseInput);
        $toLen=strlen($toBaseInput);
        $numberLen=strlen($numberInput);
        $retval='';
        if ($toBaseInput == '0123456789')
        {
            $retval=0;
            for ($i = 1;$i <= $numberLen; $i++)
                $retval = bcadd($retval, bcmul(array_search($number[$i-1], $fromBase),bcpow($fromLen,$numberLen-$i)));
            return $retval;
        }
        if ($fromBaseInput != '0123456789')
            $base10=convBase($numberInput, $fromBaseInput, '0123456789');
        else
            $base10 = $numberInput;
        if ($base10<strlen($toBaseInput))
            return $toBase[$base10];
        while($base10 != '0')
        {
            $retval = $toBase[bcmod($base10,$toLen)].$retval;
            $base10 = bcdiv($base10,$toLen,0);
        }
        return $retval;
    }

    
}
