<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
    <title>香狸文学</title>
    <link rel="stylesheet" href="/css/book_list.css?{{time()}}">
    <link rel="stylesheet" href="/css/animate.min.css">
    <script src="https://cdn.bootcss.com/jquery/1.8.0/jquery-1.8.0.min.js"></script>
</head>
<body>
    <div class="pc-book-list">
        <div class="book-list-header">
                <div class="logo-img">
                    <a href="/qrocde"><img src="/images/pasted-image.png" style='height: 100%;width:auto;'></a>
                </div>
                <ul class="nav-list">
                    <a href="/book/list?channel=1"><li class="nav-item @if($channel==1) nav-item-check @endif ">男频</li></a>
                    <a href="/book/list?channel=2"><li class="nav-item @if($channel==2) nav-item-check @endif ">女频</li></a>
                </ul>
        </div>

        <ul class="categary-list">
            @foreach ($sort as $v)
            <li class="categary-item @if($sortid == $v['id']) categary-item-check @endif "><a href="/book/list?channel={{$channel}}&sortid={{$v['id']}}">{{$v['sort']}}</a></li>
            @endforeach
            
      
        </ul>
        <div class="data-list js-data-list">
            @foreach($data as $v)
            <a href="/book/detail?articleid={{$v['id']}}" class="book-item">
                <div class="item-img">
                    <img src="{{$v['image']}}">
                </div>
                <div class="item-content">
                    
                    <div class="item-title">{{$v['title']}}</div>
                    <div class="item-desc">{{$v['desc']}}</div>
                    <div class="item-other">
                        <!-- <div class="item-times">{{$v['created_at']}}</div> -->
                        <div class="look-number">{{$v['author']}}</div><!-- 作者 -->
                        <!-- <div class="look-number">{{$v['clicks']}}</div> 观看人数 --> 
                        <!-- <div class="likes-number">7658</div> 点赞人数 --> 
                        <!-- <div class="nav-type">{{$channel == 1?'男频':'女频'}}</div> -->
                    </div>
                    
                </div>
            </a>
            @endforeach

        </div>

        <div class="read-more js-read-more">阅读更多...</div>
    </div>
    <script src="/js/common.js"></script>
    <script>
        function Page () {
            this.readMore = $(".js-read-more");
            this.DataList = $(".js-data-list");
        }
        var num = 2;
        $.extend(Page.prototype, {
            init: function() {
                this.readMore.on("click",  $.proxy(this.handleLoadMore, this))
            },
            handleLoadMore: function() {
                var channel = {{$channel}};
                var sortid = {{$sortid}};
                var _this = this;
                $.ajax({
                    url: 'getMore?channel='+channel+'&sortid='+sortid+'&page='+num,
                    dataType:'json',
                    success: function(msg){
                        var res = msg.data;
                        var em = 0;
                        for(var key in res){
                            em = 1;
                            break;
                        }

                        if(em == 0) {
                            $('.read-more').html('没有更多了');
                            return;
                        }
                        for(a of res){                        
                            var resultDom = '<a href="#" class="book-item""> <div class="item-img"> <img src="'+a.image+'"> </div> <div class="item-content"> <div class="item-title">'+a.title+'</div> <div class="item-desc">'+a.desc+'</div> <div class="item-other"> <div class="likes-number">'+a.author+'</div>  </div> </div> </a>'
                            _this.DataList.append(resultDom);
                        }
                        num++;
                    }
                }) 
            }
        });
        var page = new Page();
        page.init();
    </script>
</body>
</html>