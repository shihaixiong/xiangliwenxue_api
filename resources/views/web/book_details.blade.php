<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
    <title>香狸文学</title>
    <link rel="stylesheet" href="/css/book-details.css?{{time()}}">
    <link rel="stylesheet" href="/css/animate.min.css">
</head>
<body>
        <div class="page-wrapper">
            <div class="page-header">
                <div class="logo-img">
                    <a href="/qrocde"><img src="/images/pasted-image.png" style='height: 100%;width:auto;'></a>
                </div>
                <ul class="nav-list">
                    <li class="nav-item">{{$data['title']}}</li>
                </ul>
            </div>
            <div class="page-main">
                <div class="book-name">{{$data['subhead']}}</div>
                <div class="chapter-name"></div>
                <p class="book-content"><?php echo $data['content']?></p>
                <a href="/chapter/list?articleid={{$data['articleid']}}" class="list-icon">
                    <img src="/images/list-icon.png" class="icon-img">
                    <span class="list-text">目录</span></a>
                @if($data['chapterid'] != 1) 
                <a href="/book/detail?articleid={{$data['articleid']}}&chapterid={{$data['chapterid']-1}}" class="last-name">上一章 : {{$data['last']}}</a> 
                @endif
                @if($data['next'] != '')
                <a href="/book/detail?articleid={{$data['articleid']}}&chapterid={{$data['chapterid']+1}}" class="next-name">下一章 : {{$data['next']}}</a>
                @endif
            </div>
        </div>
    <script src="/js/common.js"></script>
</body>
</html>