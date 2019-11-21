<style type="text/css">
    .source-wrapper{
        margin-bottom: 5px;
    }
    .write-wrapper{
        display: none;
        margin-top: 5px;
    }
    .write-div{
        margin-bottom: 15px;
    }
    .my-prove{
        width: 200px;
        height: 200px;
        position: relative;
        border: 1px solid #d2d6de;
    }
    img{
        width: auto;
        height: 100%;
        margin: 0 auto;
        display: block;
    }
    .prove{
        position: absolute;
        width: 100%;
        height: 100%;
        opacity: 0;
        top: 0;
        left: 0;
        cursor: pointer;
    }
    .mylabel{
        margin-right: 20px;
    }
    .myci{
        float: left;
        margin-right: 10px;
        margin-bottom: 10px;
        width: 200px;
        height: 200px;
        position: relative;
        border: 1px solid #d2d6de;
        cursor: pointer;
    }
    .my-img{
        display: none;
    }
    .width-45-left{
        width: 45%;
        float: left;
    }
    .width-45-right{
        width: 45%;
        float: right;
    }
</style>
<div class="col-md-12">
    <div class="box box-info">
        <!-- form start -->
        <!-- <form action="/admin/book/updateChapter" method="post" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data" onsubmit="return check(this)"> -->
            <div class="box-body">
                @foreach ($data['content'] as $k=>$v)              
                <div class="form-group">
                    <!-- <label for="book_content" class="col-sm-2  control-label">文章内容 第{{$k+1}}张</label> -->
                    <div class="col-sm-8">
                        @if(!empty($v))
                        <img src="{{$v}}" style="width:80%;">
                        @endif
                    </div>
                </div>
                @endforeach
                    
                
            </div>

        <!-- </form> -->
    </div>
</div>
<script type="text/javascript" src="{{ asset('admin_style/js/jquery.min.js') }}"></script>
