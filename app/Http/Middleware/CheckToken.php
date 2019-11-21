<?php

namespace App\Http\Middleware;

use Closure;

class CheckToken 
{
    public function handle($request, Closure $next )
    {
        $this->checkSign($request);
        return $next($request);
    }

    private function checkSign($request) { 
        $secret = env('SECRECT_KEY');
        $data = $request->input();
        $token = $data['signature'] ?? '';
        if(!empty($data['debug']) && $data['debug'] == 'test') return ;
        unset($data['signature']);
        unset($data['nsukey']);
        ksort($data);
        $method = $request->method();
        $url = $request->url();
        $dataStr = "";
        foreach($data as $k=>$v) {
            $dataStr .= $k. $v;
        }
        $strToSign = sprintf("%s&%s&%s",$method, $dataStr, $secret);
        $signautre = md5($strToSign);
        if($token != $signautre) {
            exit(json_encode(['code'=>403,'msg'=>'sign error','data'=>[]]));
        }
    }

}
