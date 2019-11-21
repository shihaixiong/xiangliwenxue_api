<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 
use App\Models\AdminModel;
use App\Services\ShopService;
use App\Services\PayService;
use App\Services\UserService;
use App\Packages\alipay\alipay;
use App\Packages\wechatpay\wechatpay;
use App\Models\CommonModel;
/**
 *  @group 商城
 */

class ShopController extends BaseController
{	

    protected $shop;
    protected $pay;
    protected $user;
    public function __construct(ShopService $shop, PayService $pay,UserService $user) {
        $this->shop = $shop;
        $this->pay = $pay;
        $this->user = $user;
    }

    /**
  	 * 商品列表 api/shop/list
  	 * 成功返回1 失败返回0+msg
  	 * @response {"code":1,"msg":"success","data":{"result":{"integral":0,"goods":[{"goods_type":"A","goods":[{"id":3,"name":"\u6d4b\u8bd5\u5546\u54c1C","cover":"http:\/\/148.70.4.186\/\/upload\/goods_img\/90cf950564c6b697ccb857beee06933b.jpeg","type":"A","price":300,"sold":0},{"id":4,"name":"\u6d4b\u8bd5\u5546\u54c1D","cover":"http:\/\/148.70.4.186\/\/upload\/goods_img\/ba9cab888d7c08de68a664c66a89e24b.jpeg","type":"A","price":300,"sold":0},{"id":9,"name":"\u6d4b\u8bd5\u5546\u54c1I","cover":"http:\/\/148.70.4.186\/\/upload\/goods_img\/90cf950564c6b697ccb857beee06933b.jpeg","type":"A","price":300,"sold":0},{"id":10,"name":"\u6d4b\u8bd5\u5546\u54c1J","cover":"http:\/\/148.70.4.186\/\/upload\/goods_img\/ba9cab888d7c08de68a664c66a89e24b.jpeg","type":"A","price":300,"sold":0},{"id":11,"name":"\u6d4b\u8bd5\u5546\u54c1K","cover":"http:\/\/148.70.4.186\/\/upload\/goods_img\/90cf950564c6b697ccb857beee06933b.jpeg","type":"A","price":300,"sold":0},{"id":13,"name":"\u6d4b\u8bd5\u5546\u54c1M","cover":"http:\/\/148.70.4.186\/\/upload\/goods_img\/ba9cab888d7c08de68a664c66a89e24b.jpeg","type":"A","price":300,"sold":0},{"id":15,"name":"\u6d4b\u8bd5\u5546\u54c1O","cover":"http:\/\/148.70.4.186\/\/upload\/goods_img\/90cf950564c6b697ccb857beee06933b.jpeg","type":"A","price":300,"sold":0}]},{"goods_type":"\u8863","goods":[{"id":1,"name":"\u6d4b\u8bd5\u5546\u54c1A","cover":"http:\/\/148.70.4.186\/\/upload\/goods_img\/ba9cab888d7c08de68a664c66a89e24b.jpeg","type":"\u8863","price":300,"sold":0},{"id":5,"name":"\u6d4b\u8bd5\u5546\u54c1E","cover":"http:\/\/148.70.4.186\/\/upload\/goods_img\/90cf950564c6b697ccb857beee06933b.jpeg","type":"\u8863","price":300,"sold":0},{"id":6,"name":"\u6d4b\u8bd5\u5546\u54c1F","cover":"http:\/\/148.70.4.186\/\/upload\/goods_img\/90cf950564c6b697ccb857beee06933b.jpeg","type":"\u8863","price":300,"sold":0},{"id":8,"name":"\u6d4b\u8bd5\u5546\u54c1H","cover":"http:\/\/148.70.4.186\/\/upload\/goods_img\/90cf950564c6b697ccb857beee06933b.jpeg","type":"\u8863","price":300,"sold":0},{"id":12,"name":"\u6d4b\u8bd5\u5546\u54c1L","cover":"http:\/\/148.70.4.186\/\/upload\/goods_img\/90cf950564c6b697ccb857beee06933b.jpeg","type":"\u8863","price":300,"sold":0},{"id":14,"name":"\u6d4b\u8bd5\u5546\u54c1N","cover":"http:\/\/148.70.4.186\/\/upload\/goods_img\/90cf950564c6b697ccb857beee06933b.jpeg","type":"\u8863","price":300,"sold":0}]},{"goods_type":"\u98df","goods":[{"id":2,"name":"\u6d4b\u8bd5\u5546\u54c1B","cover":"http:\/\/148.70.4.186\/\/upload\/goods_img\/90cf950564c6b697ccb857beee06933b.jpeg","type":"\u98df","price":300,"sold":0},{"id":7,"name":"\u6d4b\u8bd5\u5546\u54c1G","cover":"http:\/\/148.70.4.186\/\/upload\/goods_img\/ba9cab888d7c08de68a664c66a89e24b.jpeg","type":"\u98df","price":300,"sold":0}]}]}}}
  	 */
    public function list(Request $r){

      $uid = '';
      if(!empty($r->header('token'))) {
            $uid = getUserid($r->header('token'));
      }

    	$arr = $this->shop->getGoodsList($uid);
      if(!$arr) apiReturn(0, '没有商品');

      apiReturn(1, '成功', ['result'=>$arr]);
      
    }

    /**
     * 商品详情 api/shop/details
     * 成功返回1 失败返回0+msg
     * @bodyParam id int required 商品id
     * @response {"id":15,"image":["http:\/\/148.70.4.186\/\/upload\/goods_img\/0f68ab9ab5d9965ce70208b77fbbca2c.jpeg","http:\/\/148.70.4.186\/\/upload\/goods_img\/d634b15b5d6a037ec7733ecebb4a04b5.jpeg","http:\/\/148.70.4.186\/\/upload\/goods_img\/94784549e95e6b93b24539d0b520cd94.jpg","http:\/\/148.70.4.186\/\/upload\/goods_img\/7eff544090162fddf5c50a2ac87a529d.jpeg"],"address_status":1,"address_res":{"name":"\u5fb7\u739b\u897f\u4e9a","tel":"16601208461","address":"\u5317\u4eac"}}}
     */
    public function details(Request $r){

      $id = $r->id;
      if(!$id) apiReturn( 0, '缺少参数');

      $arr = $this->shop->getGoodsOne($id);      
      if(!$arr) apiReturn( 0, '商品不存在');


      $arr['address_status'] = 0;
      $arr['address_res']=[
        'name'=>'',
        'tel'=>'',
        'address'=>'',
        // 'area' => '',
      ];


      if(!empty($r->header('token'))) {
        $uid = getUserid($r->header('token'));
        // $uid = 20;
        $res = (new AdminModel('address'))->getOne(['uid'=>$uid],['name','address','tel']);
        if($res){
          $arr['address_status'] = 1;
          $arr['address_res']['name'] = $res['name'];
          $arr['address_res']['tel']  = $res['tel'];
          $arr['address_res']['address'] = $res['address'];
          // $arr['address_res']['area'] = $res['area'];
        }
      }

      apiReturn( 1, '成功',$arr);

    }
    /**
     * 商品兑换 api/shop/purchase
     * 成功返回1 失败返回0+msg
     * @bodyParam id int required 商品id
     * @bodyParam tel string required 电话号码
     * @bodyParam address string required 收货地址
     * @bodyParam name string required 收货姓名
     */
    public function purchase(Request $r){

      $uid = getUserid($r->header('token'));
      // $uid =20;
      $id = $r->id;
      if(!$id) apiReturn( 0, '缺少参数');
      $tel = $r->tel; 
      if(!$tel) apiReturn( 0, '缺少联系方式');
      $address = $r->address;
      if(!$address) apiReturn( 0, '缺少地址');
      $name = $r->name;
      if(!$name) apiReturn( 0, '缺少姓名');

      $addressMolde = new AdminModel('address');
      if($addressMolde->getOne(['uid'=>$uid])){
        $addressMolde->editArray(['uid'=>$uid],[
          'name'=>$name,
          'address'=>$address,
          'tel'=>$tel,
          // 'area'=>$area,
          'updated_at'=>date('Y-m-d h:i:s',time())
        ]);
      }else{
        $addressMolde->addArray([
          'name'=>$name,
          'address'=>$address,
          'tel'=>$tel,
          // 'area'=>$area,
          'updated_at'=>date('Y-m-d h:i:s',time()),
          'uid'=>$uid,
        ]);
      }

      $user_integral = (new AdminModel('user'))->getOne(['id'=>$uid],'integral');
      $goods = (new AdminModel('goods'))->getOne(['id'=>$id],['name','stock','price','sold']);
      if($goods['stock']==0) apiReturn( 0, '库存不足');
      if($user_integral['integral']<$goods['price']) apiReturn( 0, '积分不足');
      $dates = date('Y-m-d h:i:s',time());

      DB::beginTransaction();
      try{
        //生成兑换订单
        (new AdminModel('integral_order'))->addArray([
          'uid'=>$uid,
          'goods_id'=>$id,
          'goods_name'=>$goods['name'],
          'tel'=>$tel,
          'order_num'=>time().rand(10000,99999),
          'address'=>$address,
          'name'=>$name,
          'price'=>$goods['price'],
          'created_at'=>$dates,
          'updated_at'=>$dates
        ]);
        //商品库存变化
        (new AdminModel('goods'))->editArray(['id'=>$id],['stock'=>$goods['stock']-1,'sold'=>$goods['sold']+1,'updated_at'=>$dates]);
        //用户积分变化
        (new AdminModel('user'))->editArray(['id'=>$uid],['integral'=>$user_integral['integral']-$goods['price']]);


        DB::commit();
        apiReturn( 1, '兑换成功');
      }catch (Exception $e){

        DB::rollBack();
        apiReturn( 0, '失败');
      }


    }

    /**
     * 商品兑换记录 api/shop/purchaseRecord
     * 成功返回1 失败返回0+msg
     * @response {"code":1,"msg":"success","data":{"result":[{"id":5,"name":"\u6d4b\u8bd5\u5546\u54c1E","cover":"http:\/\/148.70.4.186\/\/upload\/goods_img\/90cf950564c6b697ccb857beee06933b.jpeg","price":300,"order_num":"156040369029370","order_status":0,"express_num":null}]}}
     */
    public function purchaseRecord(Request $r){
      $uid = getUserid($r->header('token'));
       // $uid =20;
      $arr = $this->shop->integralOrderList($uid);
      if(!$arr) apiReturn(0, '没有兑换记录');
      // var_dump($arr);die;
      apiReturn(1, '成功', ['result'=>$arr]);

    }

    /**
     * alipay api/shop/pay
     * 成功返回1 失败返回0+msg
     * @response {"code":1,"msg":"success","data":{"result":"alipay_sdk=alipay-sdk-php-20180705&app_id=2019052565361337&biz_content=%7B%22body%22%3A%22tessssst%22%2C%22subject%22%3A%22test%22%2C%22out_trade_no%22%3A%221560404553%22%2C%22timeout_express%22%3A%2215m%22%2C%22total_amount%22%3A%220.01%22%2C%22product_code%22%3A%22QUICK_MSECURITY_PAY%22%7D&charset=UTF-8&format=json&method=alipay.trade.app.pay&notify_url=https%3A%2F%2Fnovel.kdreading.com&sign_type=RSA2&timestamp=2019-06-13+05%3A42%3A33&version=1.0&sign=Qp8aY%2FZel6wy9%2BlbdYSDqGVNYMd4%2F4PqpRIppTRSIzqN7JcB5JlcU0VyjF%2F551yOhZTRgdgK34cXFxhBU8FM62nNjn1Eo5X9JbRU3oYatW0cFCrDHlslRjIBEeumzp%2F%2BSZb9yNW8TmrZG4M0kowecm%2BUjZh0T9ZDlk3GZY8Kyykaf1zkTpqCJT4NgdclBZTKw3jmQM%2F7xXHjLzI17OmPRH%2ByX04zIInkTqttfFFa5TkUptKqd2o0Ij6m8lU9VzyP4iUqxo33e6c6%2FswfEb4r4jtLzQdX3ZRG5VdHtDcq8jLj3arejDW2nh6E30SgeJK3Pv%2FMvs3tjvazo7apr5wpbA%3D%3D"}}
     */
    public function pay(Request $r){
      $orderId =  time();
      $subject = env("READ_NAME"); 
      $body = env("READ_NAME"); 
      $id  = $r->input('id');
      $config = $this->pay->getPayConfig($id);
      $reward = $config['reward'] ?? 0;
      $coin = $config['coin'] + $reward;
      $total_amount =  $config['money']; 
      $expire =  15;
      $articleid = $r->input('articleid') ?? 0;
      $userid = getUserId($r->header('token'));
      $orderSn = $this->pay->makeOrder($userid, $total_amount, $articleid, $coin);
      if(!$orderSn) return apiReturn(0, '生成订单失败');
      $alipay = new Alipay();
      $res = $alipay->generateOrder($orderSn,$subject,$body,$total_amount,$expire);
      if(!$res) apiReturn(0, '生成订单失败');
      apiReturn(1, '生成订单成功', ['result'=>$res]);
     
    }

    /**
     * wechatPay api/shop/wechatpay
     * 成功返回1 失败返回0+msg
     * @response {"code":1,"msg":"success","data":{"result":{"appid":"wx38fbbcbda6059377","noncestr":"56SUrXtRENy94T4ZcUBW1T9ST7rSbnqB","package":"Sign=WXPay","partnerid":"13541502","prepayid":"wx171605579955482d30d6306d1623527600","timestamp":1560758758,"sign":"2971D5D5268585D561E77FF4CFFF3FE0","packagestr":"Sign=WXPay"}}}
     */
    public function wechatpay(Request $r){
      $body = env("READ_NAME"); 
      
      $id  = $r->input('id');
      $config = $this->pay->getPayConfig($id);
      $reward = $config['reward'] ?? 0;
      $coin = $config['coin'] + $reward;
      $total_amount = $config['money']; 
      $total_fee = $config['money'];

      // $payConfig = $this->pay->getPayConfig($id);
      // if(empty($payConfig)) return apiReturn(0, '参数有误请重试');
      // var_dump($payConfig);die;
      $articleid = $r->input('articleid') ?? 0;
      $userid = getUserId($r->header('token'));

      $orderSn = $this->pay->makeOrder($userid, $total_amount, $articleid, $coin);
      if(!$orderSn) return apiReturn(0, '生成订单失败');

      $wechatpay = new Wechatpay();
      $res = $wechatpay->Weixinpayandroid($total_fee, $orderSn );
      if(!$res) apiReturn(0, '生成订单失败');
      apiReturn(1, '生成订单成功', ['result'=>$res]);

    } 


    public function payConfig(Request $r) {
      $config = (new CommonModel('config'))->getOne();
      $payInfo = json_decode($config['pay_info'],true);
      $appleInfo = json_decode($config['apple_pay_info'],true);
      apiReturn(1, 'success',['result'=>$payInfo,'apple_pay'=>$appleInfo]);
    }


    public function googlePlayCheck(Request $r) {
      //安卓支付成功后传过来的
        $product_id = $r->input('id');
        $purchase_token = $r->input('purchaseToken');
        $package_name = 'com.xj.read';
        
        $google_client = new \Google_Client();
        $google_client->setAuthConfig("/data/www/html/read/public/api-7027410110876409578-891933-3d76f3439b2a.json");

        $google_client->useApplicationDefaultCredentials();
        // $google_client->issuer($service_account_email);
        $google_client->addScope(\Google_Service_AndroidPublisher::ANDROIDPUBLISHER);
        // $google_client->setScopes([\Google_Service_AndroidPublisher::ANDROIDPUBLISHER]);
        $androidPublishService = new \Google_Service_AndroidPublisher($google_client);
        try {
          $result = $androidPublishService->purchases_products->get(
            $package_name,
            $product_id,
            $purchase_token
          );
          if($result['acknowledgementState'] == 1) {
              //已确认
              $userid = getUserId($r->header('token'));
              $id  = $r->input('id');
              $config = $this->pay->getPayConfig($id);
              $reward = $config['reward'] ?? 0;
              $coin = $config['coin'] + $reward;
              $orderSn = $this->pay->makeOrder($userid, $config['money'], 0, $coin,$purchase_token);
              if(empty($orderSn)) {
                  apiReturn(0, '充值失败,请重试');
              }
              $res = $this->pay->callBack($orderSn, $coin);

              if($res) apiReturn(1, '充值成功');
          }
        } catch (Exception $e) {
            apiReturn(0, '充值失败,请重试');

        }

    }

    // /**
    //  * 提现 api/shop/withdraw
    //  * 成功返回1 失败返回0+msg
    //  * @bodyParam price int required 提现金额
    //  * @bodyParam type int required 提现类型1微信2支付宝
    //  */
    // public function withdraw(Request $r){

    //   $uid = getUserid($r->header('token'));
    //   // $uid = 20;

    //   $price = $r->price;
    //   if(!$price) apiReturn( 0, '缺少提现金额');
    //   $type = $r->type;
    //   if(!$type) apiReturn( 0, '缺少提现类型');

    //   $res = (new AdminModel('withdraw'))->addArray([
    //     'uid'=>$uid,
    //     'price'=>$price,
    //     'type'=>$type,
    //     'created_at'=>date('Y-m-d H:i:s',time()),
    //     'updated_at'=>date('Y-m-d H:i:s',time()),
    //   ]);
    //   if($res) apiReturn(1, 'success');
    //   apiReturn(0, '失败');
      
    // }

     /*
     * 支付宝回调处理
     *
     */
    public function alipayCallback(Request $r) {
        $post = $r->input();
        $alipay = new Alipay();
        $res = $alipay->callBack($post);
        $res = $this->pay->callBack($res['orderSn'], $res['amount']);
        if(empty($res)) exit('error');
        else exit('success');
       
    }


     /*
     * 微信支付回调处理
     */
    public function wechatCallback(Request $r) {
        $notify = new Wechatpay();

        $res = $notify->verifyNotify();
        // if( !$notify->verifyNotify() ) {
        //     //失败时必需返回，否则微信服务器将重复提交通知
        //    $notify->fail('Invalid Request');
        // }
        // 在这里处理你的业务逻辑，比如修改订单状态
        // $notify['out_trade_no'] 为订单号
        // $notify['total_fee']    订单金额
        $amount = $res['total_fee']/100;
        
        $res = $this->pay->callBack($res['out_trade_no'], $amount);

        // 返回成功标识
        if($res) exit('success');
        else exit('error');
        
    }

    public function withdrawal(Request $r) {
        $userid    = getUserId($r->header('token'));
        $money     = $r->input('money');
        $wechat_id = $r->input('wechat_id');
        if($money < 100) apiReturn(0, '提现金额不能小于100元');
        if(empty($wechat_id)) apiReturn(0, '微信号不能为空');
        //检测用户余额是否够
        $userInfo = $this->user->getUserInfo(['id'=>$userid]);
        if($money > $userInfo['money']) {
            apiReturn(0,'余额不足');
        }
        //足够的话  减用户余额  增加提现记录
        DB::beginTransaction();
        try{
          //用户余额变化
          (new AdminModel('user'))->editArray(['id'=>$userid],['money'=>$userInfo['money']-$money]);
          (new AdminModel('withdrawal'))->addArray([
            'userid'     => $userid,
            'money'      => $money,
            'created_at' => date('Y-m-d H:i:s', time()),
            'updated_at' => date( "Y-m-d H:i:s", time()),
            'status'     => 0,
            'wechat_id'  => $wechat_id,
          ]);

          DB::commit();
          apiReturn( 1, '提现成功');
        }catch (Exception $e){
          DB::rollBack();
          apiReturn( 0, '失败');
        }
    }


    public function makeOrder(Request $r) {
        //已确认
        $userid = getUserId($r->header('token'));
        $id  = $r->input('id');
        $config = (new CommonModel('config'))->getOne();
        $payInfo = json_decode($config['apple_pay_info'],true);
        $config = [];
        foreach ($payInfo as $key => $value) {
            if($value['id'] == $id) {
                $config = $value;
            }
        }
        if(empty($config)) apiReturn(0, 'error');
        $reward = $config['reward'] ?? 0;
        $coin = $config['coin'] + $reward;
        $orderSn = $this->pay->makeOrder($userid, $config['money'], 0, $coin);
        if(!empty($orderSn)) {
            apiReturn(1, 'success',['order_sn'=>$orderSn]);
        }
        // $res = $this->pay->callBack($orderSn, $coin);
        apiReturn(0, 'error');
    }

    public function applePayCheck(Request $r) {
        $appleVerifyUrl     = "https://buy.itunes.apple.com/verifyReceipt";
        $testAppleVerifyUrl = "https://sandbox.itunes.apple.com/verifyReceipt";
        $payload            = json_encode(['receipt-data' => $r->input('receipt')]);
        $receiptHash        = md5($r->input('receipt'));
        $orderInfo          = $this->pay->getOrder($r->input('order_sn'));

        if(empty($orderInfo)) apiReturn(0, '订单不存在');
        $coin = $orderInfo['coin'];

        $checkStatus = curl($appleVerifyUrl, $payload, ['Content-Type:application/json']);

        if(empty($checkStatus)) return apiReturn( 0, "支付状态获取失败{$checkStatus['status']},请重试");
        $checkStatus = json_decode($checkStatus, true);

        if(!is_array($checkStatus)) return apiReturn( 0, "支付状态获取失败{$checkStatus['status']},请重试");
        if(!isset($checkStatus['status'])) return apiReturn( 0, "支付状态获取失败{$checkStatus['status']},请重试");
        //21007:测试环境的订单 不能在正式环境验证. 审核专用.
        if($checkStatus['status'] == 21007) {
            $checkStatus = json_decode(curl($testAppleVerifyUrl, $payload, ['Content-Type:application/json']), true);
        }
        if(empty($checkStatus['receipt']['in_app'])) return apiReturn( 0, "支付失败");
        if($checkStatus['status'] != 0) {
            return apiReturn($checkStatus['status'], 0, "支付状态获取失败{$checkStatus['status']},请重试");
        }


        //支付成功  修改平台订单状态
        $res = (new CommonModel('order'))->getOne(['status'=>1,'purchase_token'=>$receiptHash]);
        if(!empty($res)) apiReturn( 0, "receipt is use");
        $res = $this->pay->callBack($r->input('order_sn'), $coin);
        if($res) {
            $res = (new CommonModel('order'))->editArray(['order_sn'=>$r->input('order_sn')],['purchase_token'=>$receiptHash]);
            return apiReturn( 1, '支付成功!');
        }

        apiReturn( 0, "支付状态获取失败{$checkStatus['status']},请重试");
    }

}