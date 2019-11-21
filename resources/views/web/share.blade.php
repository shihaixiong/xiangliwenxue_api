<!DOCTYPE html>
<!-- saved from url=(0136)https://dd.ayd8.cn/doudou/bookshare?app_id=61096114&book_id=2046199&content_id=9525187&order=1&share_id=20190718000131Ygichu7SWEhUXtbqcz -->
<html lang="en" data-dpr="1" style="font-size: 100px; margin: 0px auto; max-width: 750px;"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    
    <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <meta name="format-detection" content="telephone=no">
    <meta name="format-detection" content="email=no">
<link rel="stylesheet" href="/css/App2.css">
<link rel="stylesheet" href="/css/style2.css?{{time()}}">
<link rel="shortcut icon" href="/favicon.ico" />

<link rel="bookmark"href="/favicon.ico" />
<title>{{$data['htmlTitle']}}</title>
<script src="/js/rootFont.js"></script>
<script src="/js/jquery-1.9.1.min.js"></script>

</head>
<body style="">
<section class="padLR30">
    <header class="header_box" style="margin-top: .2rem;">
        <div class="header_box_left">
<img src="{{$data['image']}}" class="img">
        </div>
        <div class="header_box_right">
<p class="color333 mb35 font16" style="font-size: .36rem;font-weight: bold;margin-bottom: .2rem;">{{$data['title']}}</p>
<p class="color999 mb35 font13" style="font-size: .34rem;margin-bottom: .2rem;">作者：{{$data['author']}}</p>
<p class="color999 font13" style="font-size: .34rem;">状态：{{$data['finish'] ? '完本' : '连载'}}</p>
        </div>
    </header>
    <div class="desc" style="font-size: .34rem;">
<span class="color333" style="font-weight: bold;">内容简介：</span><span class="color999">{{$data['desc']}}</span>
    </div>
    <div>
        @if($data['type'] == 1)
        @foreach($data['content'] as $k=>$v)
<h3 class="chapter color333 font16" style="font-size: .36rem;">{{$v['subhead']}}</h3>
<div class="content color999 font14" style="padding-bottom: 1rem;font-size: .34rem;line-height: 150%; color:#1a1a1a;">
　　<p class="p1"><?php echo $v['content'] ?></p>    </div>
        @endforeach
        @endif
        @if($data['type'] == 2)
        @foreach($data['content'] as $k=>$v)
<h3 class="chapter color333 font16" style="font-size: .36rem;">{{$v['subhead']}}</h3>
<div class="content color999 font14" style="padding-bottom: 1rem;font-size: .34rem;line-height: 150%; color:#1a1a1a;">
        @foreach($v['content'] as $img)
            <img src="{{$img}}" style="width:100%">
        @endforeach
</div>
        @endforeach
        @endif

    </div>
    <div class="down_box">
<div class="left"><img src="/images/ic_launcher.png" class="logo"></div>
        <div class="center font14 color333"><?php echo $data['msg'];?></div>
<a class="down font13" onclick="showBox()" data-href="http://wap.xiangliwenxue.com/" id="apk_url">点击下载</a>
    </div>
    <div class="dialog" onclick="hideBox()" style="display: none">
        <div class="modal">
            <a class="close"></a>
            <p class="warn">温馨提示</p>
            <p class="word font16 color666">iOS版本正在开发中，敬请期待!</p>
        </div>
    </div>
</section>
<script>
    
    function hideBox() {
        document.querySelector('.dialog').style.display = 'none'
    }
    function showBox() {
        var u = navigator.userAgent;
        if(/(iPhone|iPad|iPod|iOS)/i.test(u)){
           // document.querySelector('.dialog').style.display = 'block'
           
           window.location.href = "http://wap.xiangliwenxue.com/";

            if(!document.hidden)
            {
                window.setTimeout(function(){
                    if(!document.hidden)
                    {
                        window.location.href = '#';
                    }
                },2000);
            }


        }else{
//window.location.href = $('#apk_url').data("href");

            //window.location.href = "doudou://kanshu/main";
window.location.href =
"http://wap.xiangliwenxue.com/";

if(!document.hidden)
{
            window.setTimeout(function(){

                if(!document.hidden)
                {

var ua = navigator.userAgent.toLowerCase();
var isWeixin = ua.indexOf('micromessenger') != -1;
if (isWeixin) {
window.location.href = "http://wap.xiangliwenxue.com/";
}
else
{
window.location.href = $('#apk_url').data("href");
}
                    
                }

            },2000);

}


        }
    }
</script>