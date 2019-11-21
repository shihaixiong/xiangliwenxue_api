<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
    <title>香狸文学</title>
    <link rel="stylesheet" href="/css/chapter_list.css">
    <link rel="stylesheet" href="/css/animate.min.css">
</head>
<body>
    
    <div class="page-wrapper">
        <div class="page-header">
            <div class="logo-img">
                <a href="/qrocde"><img src="/images/pasted-image.png" style='height: 100%;width:auto;'></a>
            </div>
            <ul class="nav-list">
               
            </ul>
        </div>

        <div class="page-main">
            <a class="close-btn animated rotateIn" href="#"></a>
            <div class="title">目录</div>
            @foreach($data as $k=>$v)
            <a href="/book/detail?articleid={{$v['book_id']}}&chapterid={{$k+1}}" class="item-name item-name-check animated bounceInLeft">{{$v['subhead']}}</a>
            @endforeach
          
        </div>
    </div>
    <script src="/js/common.js"></script>
</body>
</html>