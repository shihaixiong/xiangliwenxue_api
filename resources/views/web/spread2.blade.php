<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:th="http://www.thymeleaf.org">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport"
          content="initial-scale=1, width=device-width, maximum-scale=1, minimum-scale=1,user-scalable=no"/>
    <meta name="format-detection" content="telephone=no,email=no,adress=no"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black"/>
    <meta name='apple-touch-fullscreen' content='yes'/>
    <meta name="full-screen" content="yes"/>
    <meta content="email=no" name="format-detection"/>
    <meta name="wap-font-scale" content="no"/>
    <title>{{$header_title}}</title>
    <link href="/css/app4.css" rel="stylesheet"/>
</head>
<body class="index2">
<header class="h2">
    <p>{{$spread['title']}}</p>
</header>
<main>
    @if(!empty($image))
    <img src="{{$image}}">
    @endif

    <div class="content">
    @foreach($content as $k=>$v)
        <p class="p1"><?php echo $v['content'];?></p>
        @if(!empty($images[$k]))
        <img class="cc" src="{{$images[$k]}}">
        @endif
        @endforeach
        <div class="waiting">
            <!-- <img src="/images/waiting.png"> -->
            <span>* {{$spread['short_rec']}} *</span>
        </div>
    </div>
</main>
<a href="/downUrl?id={{$id}}">

<footer>
    <p class="p3">{{$spread['start_view_num']+$spread['view_num']}}{{$msg}}</p>
    <div class="header">
        @foreach($logo as $v)
        <img src="{{$v}}">
        @endforeach
    </div>
    <p class="p4">{{$footer}}</p>
    <img src="/images/dl.png">
</footer>
</a>
</body>
</html>
