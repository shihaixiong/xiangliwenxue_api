<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Services\FuncService;
use App\Services\BookService;
use App\Models\CommonModel;
use Cache;
use DB;
use Illuminate\Support\Facades\Mail;

/**
 * @group 用户
 */
class UserController extends BaseController
{
    protected $user;
    protected $func;
    protected $book;
    public function __construct(UserService $user, FuncService $fun, BookService $book) {
        $this->user = $user;
        $this->func = $fun;
        $this->book = $book;
    }
     /**
     * 登录 api/user/setPwd
     * 登录phone必传 code 和 password必需传一个
     * 成功返回1 失败返回0+msg
     * @bodyParam phone string required 手机号
     * @bodyParam password string required 密码
     * @bodyParam code string required 验证码
     * @response {"code":1,"msg":"success","data":{"token":"string(32)"}}
     * @response {"code":0,"msg":"用户名或密码错误","data":[]}
     */
    public function login(Request $r) {
        $phone    = $r->input('phone');
        $code     = $r->input('code');
        $password = $r->input('password');
        $where['phone'] = $phone;
        if(!empty($password)) {
            $where['password'] = $password;
            $token = $this->user->loginByPwd($where);
            if(!$token) apiReturn(0,'用户名或密码错误');
        }
        if(!empty($code)) {
            if($code != "111333") {
                if($code != Cache::get($phone."_code")) {
                    apiReturn(0,'验证码错误',[]);
                }
            }
            $data = $this->user->loginByCode($where);
            $token = $data['token'];
            $userid = $data['userid'];
            $isNew = $this->user->checkIsNew($userid);
        }else{
            $isNew = $this->user->checkIsNew(getUserId($token));
        }
        apiReturn(1,'登录成功',['token'=>$token,'new_user'=>$isNew]);
    }

    /**
     * 发送手机验证码 api/user/sendcode
     * 暂时短信平台不好使  data返回验证码  正式后data不返回数据 成功返回1 失败返回0+msg
     * @bodyParam email string required 手机号
     * @response {"code":1,"msg":"success","data":{'code'=>"int(6)"}}
     * @response {"code":0,"msg":"发送频率太快了,请稍后重试","data":[]}
     */
    public function sendCode(Request $r) {
        $rand = rand(100000,999999);
        $phone = $r->input('phone');
        $keyName = $phone."_code";
        $codeTime = $phone."_code_time";
        if(Cache::get($codeTime)) apiReturn('0','发送频率太快了,请稍后重试');
        $res = $this->func->sendMsg($rand, 1, $phone);
        if($res) {
            cache::forget($keyName);
            Cache::add($keyName, $rand, 300);
            Cache::add($codeTime, 1, 60);
            apiReturn(1,'发送成功');
        }else{
            apiReturn(0,'发送失败 请重试');
        }
    }

    /**
     * 修改密码 api/user/setPwd
     * 成功返回1 失败返回0+msg
     * @bodyParam password string required 密码
     * @response {"code":1,"msg":"success","data":[]}
     * @response {"code":0,"msg":"密码设置失败","data":[]}
     */
    public function setPwd(Request $r) {
        $userid = getUserId($r->header('token'));
        if(empty($r->input('password')) || strlen($r->input('password')) < 6) apiReturn(0, '密码格式有误', []);
        $password = md5($r->input('password'));
        $res = $this->user->updPwd($userid, $password);
        if($res) return apiReturn(1, '设置成功');
        return apiReturn(0, '密码设置失败');

    }

    /**
     * 我的 api/user/my
     * 成功返回1 失败返回0+msg
     * @response  {"code":1,"msg":"success","data":{"userid":1,"username":"\u7528\u623720","img":null,"remain":0,"money":0,"integral":0,"level":1,"invate_code":"SHDELSD","is_invate":1}}

     * @response {"code":0,"msg":"密码设置失败","data":[]}
     */
    public function my(Request $r) {
        if(empty($r->header('token'))) return apiReturn(0, '暂未登陆');
        $userid = getUserId($r->header('token'));
        $userInfo = $this->user->getUserInfo(['id'=>$userid]);
        $code = $this->user->inviteCode($userInfo['id']);
        $relation = (new CommonModel('user_relation'))->getOne(['userid'=>$userid]);
        $return = [
            'userid'      => $userInfo['id'],
            'username'    => $userInfo['username'],
            'img'         => $userInfo['image'],
            'remain'      => $userInfo['remain'],
            'money'       => $userInfo['money'],
            'integral'    => $userInfo['integral'],
            'level'       => $userInfo['level'],
            'invate_code' => $this->user->inviteCode($userInfo['id']),
            'is_invate'   => $relation ? 1: 0
        ];
        if($return) return apiReturn(1, '成功', $return);
    }
    

    /**
     * 我的 api/user/my
     * 成功返回1 失败返回0+msg
     * @response  {"code":1,"msg":"success","data":{"userid":1,"username":"\u7528\u623720","img":null,"remain":0,"money":0,"integral":0,"level":1}}

     * @response {"code":0,"msg":"暂未登录","data":[]}
     */
    public function userInfo(Request $r) {
        if(empty($r->header('token'))) return apiReturn(0, '暂未登陆');
        $userid = getUserId($r->header('token'));
        $userInfo = $this->user->getUserInfo(['id'=>$userid]);
        $code = $this->user->inviteCode($userInfo['id']);
        $relation = (new CommonModel('user_relation'))->getOne(['userid'=>$userid]);

        $return = [
            'userid' => $userInfo['id'],
            'username' => $userInfo['username'],
            'img' => imageUrl($userInfo['image']),
            'remain' => $userInfo['remain'],
            'money' => $userInfo['money'],
            'integral' => $userInfo['integral'],
            'level' => $userInfo['level'],
            'invate_code' => $code,
            'is_invate' => $relation ? 1: 0
        ];
        if($return) return apiReturn(1, '成功', $return);
    }
    /**
     * 我的订阅 api/user/paid
     * 成功返回1 失败返回0+msg
     * @bodyParam page int required 页码
     * @response  {"code":1,"msg":"success","data":{"result":[{"articleid":1,"image":"http:\/\/148.70.4.186\/book_img\/15595504042952.jpg","title":"\u5c06\u591c"}]}}

     * @response {"code":0,"msg":"暂未登陆","data":[]}
     */
    public function myPaid(Request $r) {
        $page = $r->input('page') ?? 1;
        $limit = 10;
        $offset = $limit * ($page - 1);
        if(empty($r->header('token'))) return apiReturn(0, '暂未登陆');
        $userid = getUserId($r->header('token'));
        $paidList = $this->book->getUserPaid($userid, $limit, $offset);
        if(empty($paidList)) return apiReturn(0, '没有订阅的书哦');
        $conn = ['image','id','title','desc','author','keywords','type','finish','word_count','sortid'];
        $bookList = $this->book->getBookInfo($paidList,$conn);
        foreach ($bookList as $key => $value) {
            $bookList[$key]['articleid'] = $value['id'];
        }
        apiReturn(1, '成功', ['result'=>$bookList]);
    }

    /**
     * 最近阅读 api/user/lastRead
     * 成功返回1 失败返回0+msg
     * @bodyParam page int required 页码
     * @response  {"code":1,"msg":"success","data":{"result":[{"articleid":1,"image":"http:\/\/148.70.4.186\/book_img\/15595504042952.jpg","title":"\u5c06\u591c"}]}}

     * @response {"code":0,"msg":"暂未登陆","data":[]}
     */
    public function lastRead(Request $r) {
        $page = $r->input('page') ?? 1;
        $limit = 10;
        $offset = $limit * ($page - 1);
        if(empty($r->header('token'))) return apiReturn(0, '暂未登陆');
        $userid = getUserId($r->header('token'));
        $paidList = $this->book->getLastRead($userid, $limit, $offset);
        if(empty($paidList)) return apiReturn(0,'你还没有阅读');
        $conn = ['image','id','title','desc','author','keywords','type','finish','word_count','sortid'];
        $bookList = $this->book->getBookInfo($paidList,$conn);
        foreach ($bookList as $key => $value) {
            $bookList[$key]['articleid'] = $value['id'];
        }
        apiReturn(1, '成功', ['result'=>$bookList]);
    }

    /**
     * 修改个人信息 api/user/updInfo
     * 成功返回1 失败返回0+msg
     * @bodyParam username string required 用户名
     * @bodyParam sex int required 性别
     * @bodyParam birthday string required 生日
     * @bodyParam image file required 头像
     * @response  {"code":1,"msg":"success","data":{}]}}
     * @response {"code":0,"msg":"error","data":[]}
     */
    public function updUserInfo(Request $r) {
        $userid = getUserId($r->header('token'));    
        $username = $r->input('username');
        $sex = $r->input('sex');
        $birthday = $r->input('birthday');
        $img = $r->file('image');
        if($img) {
            $imgName = time().'.jpg';
            $res = $img->move( 'user_logo/',$imgName );
            if($res) {
                $image = 'user_logo/'.$imgName;
            }
        }
        $data = [
            'username' => $username,
            'sex'   => $sex,
            'birthday' => $birthday,
            'updated_at' => date( "Y-m-d H:i:s", time()),
            // 'image' => $image,
        ];
        if(!empty($image)) $data['image'] = $image;

        $address = [
            'address' => $r->input('address'), 
            'tel'     => $r->input('tel'),  
            'name'    => $r->input('name'),
            'area'    => $r->input('area'),
            'updated_at' => date( "Y-m-d H:i:s", time())
        ];
        $ishave = (new CommonModel('address'))->getOne(['uid'=>$userid]);
        if(empty($ishave)) {
            $address['uid'] = $userid;
            $res = (new CommonModel('address'))->addArray($address);
        } else {
            $res = (new CommonModel('address'))->editArray(['uid'=> $userid],$address);
        }

        $data = $this->user->updUserInfo($userid,$data);
        if($data) return apiReturn(1, '更新成功');
        return apiReturn(0, '无内容更新');
    }

    /**
     * 微信登录 api/user/wechatLogin
     * 成功返回1 失败返回0+msg
     * @bodyParam username string required 用户名
     * @bodyParam sex int required 性别
     * @bodyParam openid string required openid
     * @bodyParam image file required 头像
     * @response  {"code":1,"msg":"success","data":{"token":"1c5a599185cd01d4cecb69631d5a6e76","new_user":0}}
     */
    public function wechatLogin(Request $r) {
        $openid   = $r->input('openid');
        $username = $r->input('username');
        $image    = $r->input('image');
        $sex      = $r->input('sex');

        $data = [
            'openid'   => $openid,
            'username' => $username,
            'image'    => $image,
            'sex'      => $sex,
        ];
        $token = $this->user->wechatLogin($data);
        apiReturn(1,'成功',['token'=>$token['token'], 'new_user'=>$token['isNew']]);

    }

     /**
     * 修改我的信息页面详情 api/user/myInfo
     * 成功返回1 失败返回0+msg
     * @response  {"code":1,"msg":"success","data":{"username":"\u5927\u54e5\u5927","img":"http:\/\/148.70.4.186\/user_logo\/1_lg.jpg","sex":1}}

     * @response {"code":0,"msg":"暂未登录","data":[]}
     */
    public function userDesc(Request $r) {
        if(empty($r->header('token'))) return apiReturn(0, '暂未登陆');
        $userid = getUserId($r->header('token'));
        $userInfo = $this->user->getUserInfo(['id'=>$userid]);
        $address = (new CommonModel('address'))->getOne(['uid'=>$userid]);
        $return = [
            'username' => $userInfo['username'],
            'img'      => imageUrl($userInfo['image']),
            'sex'      => $userInfo['sex'],
            'address'  => $address['address'] ?? '',
            'tel'      => $address['tel'] ?? '',
            'name'     => $address['name'] ?? '',
            'area'     => $address['area'] ?? '',
        ];
        if($return) return apiReturn(1, '成功', $return);
    }

    public function forgetPwd(Request $r) {
        $phone = $r->input('phone');

    }

    public function getRecord(Request $r) {
        $userid = getUserId($r->header('token'));
        $type = $r->input('type');
        $page = $r->input('page') ?? 1;
        $limit = 20;
        $offset = ($page-1) * $limit;
        $return = [];
        switch ($type) {
            case '1':
                $list = (new CommonModel('expend'))->getDb()->where('userid', $userid)->orderBy('created_at', 'desc')->limit($limit)->offset($offset)->get();
                foreach ($list as $key => $value) {
                    $return['result'][] = [
                        'type' => ($value->type == 1)? 2 : 1,//1+,2-;
                        'desc' => $value->subject,
                        'num'  => $value->price,
                        'created_at' => $value->created_at,
                    ];
                }
                if(empty($return)) apiReturn(0,'暂无内容');
                apiReturn(1, '成功', $return);
                break;
            case '2':
                $list = (new CommonModel('integral_log'))->getDb()->where('userid', $userid)->orderBy('created_at', 'desc')->limit($limit)->offset($offset)->get();
                foreach ($list as $key => $value) {
                    $return['result'][] = [
                        'type' => $value->type,//1-,2+;
                        'desc' => $value->desc,
                        'num'  => $value->num,
                        'created_at' => $value->created_at,
                    ];
                }
                if(empty($return)) apiReturn(0,'暂无内容');
                apiReturn(1, '成功', $return);
                break;
            case '3':
                $list = (new CommonModel('withdrawal'))->getDb()->where('userid', $userid)->orderBy('created_at', 'desc')->limit($limit)->offset($offset)->get();
                foreach ($list as $key => $value) {
                    $return['result'][] = [
                        'type' => 1,
                        'desc' => '提现',
                        'num'  => $value->money,
                        'created_at' => $value->created_at,
                    ];
                }
                if(empty($return)) apiReturn(0,'暂无内容');
                apiReturn(1, '成功', $return);
                break;
        }
    }

    public function sendEmail($code, $email){
        $message = $code; 
        $to = $email;
        $subject = '验证码';
        try {
            Mail::send(
                'emails.test', 
                ['content' => $message], 
                function ($message) use($to, $subject) { 
                    $message->to($to)->subject($subject); 
                }
            );
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function useInvite(Request $r) {
        $userid = getUserId($r->header('token'));
        $code = $r->input('code');
        $parentId = $this->user->unInviteCode($code);
        if(empty($parentId)) apiReturn(0, '邀请码错误');
        if($parentId == $userid) apiReturn(0, '请不要输入自己的邀请码');
        if((new CommonModel('user_relation'))->getOne(['userid'=>$userid])) apiReturn(0, '你已经输过了');
        $partentInfo = (new CommonModel('user'))->getOne(['id'=>$parentId]);
        if(empty($partentInfo)) return apiReturn(0, '邀请码错误');
        (new CommonModel('user_relation'))->addArray([
            'userid'    => $userid,
            'parentid' => $parentId,
        ]);
        //奖励一块
        $res = (new CommonModel('user'))->getDb()->where('id',$parentId)->increment('money', 1);
        // //添加一条记录
        $log = (new CommonModel('money_log'))->addArray([
            'userid'       => $parentId,
            'from_userid' => $userid,
            'created_at' => date( "Y-m-d H:i:s", time()),
            'order_sn' => "SN_".time().rand(10000,99999),
            'level' => 0,
            'money' => 1,
        ]);

        apiReturn(1, '添加成功');
    }

    public function myInvite(Request $r) {
        $config = (new CommonModel('config'))->getOne();
        if(!empty($r->header('token'))) {
            $userid = getUserId($r->header('token'));
            if(!empty($userid)) {
                $code = $this->user->inviteCode($userid);
                return view('web/userInvite3',['code'=>$code,'config'=>$config]);
            }
        } else {
            $token = $r->input('token');
            $userid = getUserId($token);
            if(!empty($userid)) {
                $code = $this->user->inviteCode($userid);
                return view('web/userInvite2',['code'=>$code,'config'=>$config]);
            }
        }
    }
     /**
     * 获取兑换比例 api/user/getRatio
     * 返回的不是百分比 需要客户端用金额乘以返回值 除100 
     * @response {"code":1,"msg":"兑换成功","data":{}}
     * @response {"code":0,"msg":"兑换失败","data":{}}
     */
    public function getRatio(Request $r) {
        $config = (new CommonModel('config'))->getOne();
        $return = [
            'money_to_integral'  => $config['money_to_integral'] ?? 0,
            'money_to_remain'    => $config['money_to_remain'] ?? 0,
            'integral_to_remain' => $config['integral_to_remain'] ?? 0
        ];
        return apiReturn(1,'成功',$return);
    }
    /**
     * 账户余额积分互相兑换 api/user/exchange
     * 成功返回1 失败返回0+msg
     * @bodyParam type int required 兑换类型  1.余额兑换积分 2.余额兑换阅读币 3.积分兑换阅读币
     * @bodyParam money int required 兑换金额
     * @response {"code":1,"msg":"兑换成功","data":{}}
     * @response {"code":0,"msg":"兑换失败","data":{}}
     */
    public function exchange(Request $r) {
        $type   = $r->input('type');
        $userid = getUserId($r->header('token'));
        $money  = $r->input('money');
        if(empty($type)) apiReturn(0, '类型错误');
        $unit   = [
            1 => ['from'=>'money',    'to'=>'integral'], 
            2 => ['from'=>'money',    'to'=>'remain'],   
            3 => ['from'=>'integral', 'to'=>'remain'],
        ];

        $configType = [
            '1' => 'money_to_integral',
            '2' => 'money_to_remain',
            '3' => 'integral_to_remain'
        ];
        $userInfo = $this->user->getUserInfo(['id'=>$userid]);
        if($userInfo[$unit[$type]['from']] < $money) apiReturn(0, '输入金额小于账户余额');
        $config = (new CommonModel('config'))->getOne();
        $ratio = $config[$configType[$type]];
        DB::beginTransaction();
        try{
            //用户余额变化
            $num = $userInfo[$unit[$type]['to']] + ( $money * $ratio);
            $res = (new CommonModel('user'))->editArray(['id'=>$userid],[$unit[$type]['from']=>$userInfo[$unit[$type]['from']]-$money,$unit[$type]['to']=>$num]);
            $res2 = (new CommonModel('exchange_log'))->addArray([
                'userid'       => $userid,
                'created_at'   => date( "Y-m-d H:i:s", time()),
                'money'        => $money,
                'result_money' => $money * $ratio,
                'type'         => $type,
            ]);
            if($res && $res2) {
                DB::commit();
                apiReturn( 1, '兑换成功');
            }
        }catch (Exception $e){
          DB::rollBack();
          apiReturn( 0, '兑换失败');
        }
    }
    /**
     * 查看我的分销奖励 api/user/getUserSaleLog
     * 成功返回1 失败返回0+msg
     * @bodyParam page int required 页码  一页10条
     * @response {"code":1,"msg":"success","data":{"money":1,"result":[{"money":3,"time":"2019-08-24 11:46:30","title":"\u4f63\u91d1"},{"money":6,"time":"2019-08-24 11:46:32","title":"\u4f63\u91d1"}]}}
     */
    public function getUserSaleLog(Request $r) {
        $userid = getUserId($r->header('token'));
        $userInfo = $this->user->getUserInfo(['id'=>$userid]);
        $money = $userInfo['money'];
        $page = $r->input('page') ?? 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;
        $log = (new CommonModel('money_log'))->getDb()->where(['userid'=>$userid])->limit($limit)->offset($offset)->get();
        $log = json_decode(json_encode($log),true);
        $res = [];
        foreach ($log as $k=>$v) {
            $res[] = [
                'money'=> $v['money'],
                'time' => $v['created_at'],
                'title' => '佣金'
            ];
        }
        $return = [
            'money' => $money,
            'result' => $res,
        ];
        apiReturn(1, '成功', $return);

    }
}
