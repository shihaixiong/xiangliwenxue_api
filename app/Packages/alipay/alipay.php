<?php


namespace app\Packages\alipay;
use Mockery\CountValidator\Exception;
use App\Services\PayService;
require ('AopSdk.php');

class Alipay
{
    /**
     * 生成APP支付订单信息
     * @param string $orderId   商品订单号
     * @param string $subject   支付商品的标题
     * @param string $body      支付商品描述
     * @param float $total_amount  商品总支付金额
     * @param int $expire       支付过期时间，分
     * @return bool|string  返回支付宝签名后订单信息，否则返回false
     */
    public function generateOrder($orderId, $subject, $body, $total_amount, $expire){
        // try{
            $aop = new \AopClient();
            $aop->gatewayUrl = config('alipay.gatewayUrl');
            $aop->appId = config('alipay.app_id');
            $aop->rsaPrivateKey = config('alipay.private_key');
            $aop->alipayrsaPublicKey = config('alipay.public_key');
            $aop->format= 'json';//固定
            $aop->charset = config('alipay.charset');
            $aop->signType = config('alipay.sign_type');
            $request = new \AlipayTradeAppPayRequest();
            //SDK已经封装掉了公共参数，这里只需要传入业务参数
            $bizcontent = "{\"body\":\"{$body}\","      //支付商品描述
                . "\"subject\":\"{$subject}\","        //支付商品的标题
                . "\"out_trade_no\":\"{$orderId}\","   //商户网站唯一订单号
                . "\"timeout_express\":\"{$expire}m\"," //该笔订单允许的最晚付款时间，逾期将关闭交易。取值范围：1m～15d。m-分钟，h-小时，d-天，1c-当天（1c-当天的情况下，无论交易何时创建，都在0点关闭）。 该参数数值不接受小数点， 如 1.5h，可转换为 90m。
                                                        //注：若为空，则默认为15d。
                . "\"total_amount\":\"{$total_amount}\"," //订单总金额，单位为元，精确到小数点后两位，取值范围[0.01,100000000]
                . "\"product_code\":\"QUICK_MSECURITY_PAY\""
                . "}";
            $request->setNotifyUrl(config('alipay.notify_url'));
            $request->setBizContent($bizcontent);
            
            //这里和普通的接口调用不同，使用的是sdkExecute
            $response = $aop->sdkExecute($request);
            //htmlspecialchars是为了输出到页面时防止被浏览器将关键参数html转义，实际打印到日志以及http传输不会有这个问题
            
            return $response;//就是orderString 可以直接给客户端请求，无需再做处理。
       //  }catch(Exception $e){
       // 　　　//失败返回false
       //      return false;
       //  }
    }


    public function callback($post){
        $orderSn = $post['out_trade_no'];
        $amount = $post['total_amount'];
        $aop = new \AopClient();
        $aop->alipayrsaPublicKey = config('alipay.public_key');
        $sign_type = "RSA2";
        $flag = $aop->rsaCheckV1($_POST, NULL, $sign_type);
        $flag = 1;
        if($flag){
            switch ($post['trade_status']) {
                case 'TRADE_SUCCESS': //交易成功
                    return ['orderSn'=>$orderSn, 'amount'=>$amount];
                    if($res){
                        echo 'success';
                    }
                    break;
                case 'WAIT_BUYER_PAY': //等待买家付款
                    return;
                case 'TRADE_CLOSED'://未付款交易超时关闭，或支付完成后全额退款
                     // $this->addErrorLog(13, "此条支付条目被返回支付失败-{$orderSn}-return_server_alipay_app"); 
                    break;
            }
        }else{
            // $this->addErrorLog(14, "检测sign值错误-{$orderSn}-return_server_alipay_app"); 
            exit();
        }
    }
}