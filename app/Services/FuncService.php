<?php

namespace App\Services;

use Cache;
use App\Models\CommonModel;
use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;
/**
 * Class EmailService
 *
 * @package \App\Services
 */
class FuncService{

  public function sendMsg($msg,$type,$phone){
      // Download：https://github.com/aliyun/openapi-sdk-php
      // Usage：https://github.com/aliyun/openapi-sdk-php/blob/master/README.md
      $id = env('SEND_CODE_ID');
      $key = env('SEND_CODE_KEY');

      $typeList = [
       '变更验证','登录验证','注册验证','身份验证',' 活动验证','讯海科技'
      ];

      $tempList = [
        'SMS_13048049','SMS_166371081',
      ];

      // ['singName'=>'变更验证','templateCode'=>'SMS_13048049']
      // echo 123;die;
      AlibabaCloud::accessKeyClient($id, $key)
                              ->regionId('cn-hangzhou') // replace regionId as you need
                              ->asDefaultClient();

      $result = AlibabaCloud::rpc()
                            ->product('Dysmsapi')
                            // ->scheme('https') // https | http
                            ->version('2017-05-25')
                            ->action('SendSms')
                            ->method('POST')
                            ->options([
                                        'query' => [
                                            'PhoneNumbers'  => $phone,
                                            'SignName'      => $typeList[$type],
                                            'TemplateCode'  => "SMS_13048049",
                                            'TemplateParam' => '{"code":"'.$msg.'","product":"'.env("READ_NAME").'"}',
                                        ]
                                      ])
                            ->request();
      if(!empty($result["Code"]) && $result["Code"] == "OK") return true;
      return false;
    
  }
  public function sendOrderMsg($msg,$type,$phone, $goodsName){
      // Download：https://github.com/aliyun/openapi-sdk-php
      // Usage：https://github.com/aliyun/openapi-sdk-php/blob/master/README.md
      $id = env('SEND_CODE_ID');
      $key = env('SEND_CODE_KEY');

      $typeList = [
       '活动验证','活动验证'
      ];

      $tempList = [
        'SMS_13048049','SMS_166371081',
      ];

      // ['singName'=>'变更验证','templateCode'=>'SMS_13048049']
      // echo 123;die;
      AlibabaCloud::accessKeyClient($id, $key)
                              ->regionId('cn-hangzhou') // replace regionId as you need
                              ->asDefaultClient();

      $result = AlibabaCloud::rpc()
                            ->product('Dysmsapi')
                            // ->scheme('https') // https | http
                            ->version('2017-05-25')
                            ->action('SendSms')
                            ->method('POST')
                            ->options([
                                        'query' => [
                                            'PhoneNumbers'  => $phone,
                                            'SignName'      => $typeList[$type],
                                            'TemplateCode'  => "SMS_166371081",
                                            'TemplateParam' => '{"productname":"'.$goodsName.'","codenumber":"'.$msg.'"}',
                                        ]
                                      ])
                            ->request();
      if(!empty($result["Code"]) && $result["Code"] == "OK") return true;
      return false;

    
  }
}
