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
        /*width: 200px;*/
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
    
        <!-- form start -->
        <form action="/admin/share/addspread" method="post" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data" onsubmit="return check(this)">
            <div class="box-body">
                
                <div class="form-group">
                    <label for="title" class="col-sm-2  control-label">查询书id</label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                            <input type="text" name="check_title" id="check_title" class="form-control"  placeholder="请输入书名">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="title" class="col-sm-2  control-label">推荐语</label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                            <input type="text" name="title" id="title" class="form-control" placeholder="请填写推荐语">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="articleid" class="col-sm-2  control-label">书籍id</label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                            <input type="text" name="articleid" id="articleid" class="form-control" placeholder="查询完书id 会自动输入">
                        </div>
                    </div>
                </div>

                 <div class="form-group">
                    <label for="start_num" class="col-sm-2  control-label">开始章节数</label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                            <input type="text" name="start_num" id="start_num" class="form-control" maxlength="150" value="1">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="show_num" class="col-sm-2  control-label">总共显示章节数</label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                            <input type="text" name="show_num" id="show_num" class="form-control" value="3">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="style" class="col-sm-2  control-label">推荐样式</label>
                    <div class="col-sm-8">
                        <select class="select2 form-control" id="style" name="style">
                            <option value="1" selected>有标题</option>
                            <option value="2">无标题</option>
                            
                            
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="language" class="col-sm-2  control-label">简/繁体</label>
                    <div class="col-sm-8">
                        <select class="select2 form-control" id="language" name="language">
                            <option value="1" selected>简体</option>
                            <option value="2">繁体</option>
                            
                            
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="short_rec" class="col-sm-2  control-label">末尾推荐语</label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                            <input type="text" name="short_rec" id="short_rec" class="form-control" placeholder="">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="start_view_num" class="col-sm-2  control-label">起始点击数</label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                            <input type="text" name="start_view_num" id="start_view_num" class="form-control" placeholder='只能填写数字'>
                        </div>
                    </div>
                </div>
                
               <div class="form-group">
                    <label for="zhu" class="col-sm-2 control-label">顶部图片</label>
                    <div class="col-sm-8">
                        <div class="my-prove">
                            <img src="" class="my-img">
                            <input type="file" name="pic" class="prove" >
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="zhu" class="col-sm-2 control-label">章节顶部图片</label>
                    <div class="col-sm-8 images" >
                        <div class="my-prove">
                            <img src="" class="my-img">
                            <input type="file" name="images[]" class="prove" >
                        </div>
                        <div class="my-prove">
                            <img src="" class="my-img">
                            <input type="file" name="images[]" class="prove" >
                        </div>
                        <div class="my-prove">
                            <img src="" class="my-img">
                            <input type="file" name="images[]" class="prove" >
                        </div>
                    </div>
                </div>
            </div>

            <div class="box-footer">
                <div class="col-md-2"></div>
                <div class="col-md-8">
                    
                    <div class="btn-group">
                        <button type="submit" class="btn btn-warning">确定</button>
                    </div>
                </div>
            </div>
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
        </form>
    </div>
</div>
<script type="text/javascript" src="{{ asset('admin_style/js/jquery.min.js') }}"></script>

<div id="add_prove" style="display: none;">
    <div class="my-prove"><img src="" class="my-img"><input type="file" name="images[]" class="prove" ></div>

</div>
<script type="text/javascript">
    $(".prove").on("change",function(){
        var that= $(this);
        var my_img = that.prev('img');
        var file = that[0].files[0];
        var reads= new FileReader();
        reads.readAsDataURL(file);
        reads.onload=function (e) {
            my_img.attr('src',e.target.result)
            my_img.show()
        };
    })

    $("#show_num").blur(function(){
        var num = $(this).val();
        var div = $('#add_prove').html();
        $('.images').html("");
        for (var i = 1; i <= num; i++) {
            $('.images').append(div);
        }
    })

    $("#word_count").keyup(function(){
         var c=$(this);  
         if(/[^\d]/.test(c.val())){//替换非数字字符  
          var word_count=c.val().replace(/[^\d]/g,'');  
          $(this).val(word_count);  
         }  
    })
    function check(form){
        var title          = $("#title").val();
        var articleid      = $("#articleid").val();
        var short_rec      = $("#short_rec").val();
        var start_view_num = $("#start_view_num").val();

        if(title=='' || articleid=='' || short_rec=='' || start_view_num==0){
            alert("请填写或下拉选择所有信息！不能为空！不能为未选择")
            return false;
        }else{
            
            return true;
        }

        
    }

    $("#check_title").blur(function(){
        var title = $("#check_title").val();
        var a = $(this);
        $.ajax({
            url: '/admin/recommend/getBookId',
            data:{
                title:title
            },
            success: function(msg){
                if(msg.code==1){
                     
                     $("#asp").remove();
                     $("#sure").val(1);
                     a.after("<span id='asp' style='color:blue'>书籍id:"+msg.data.id+"</span>");
                     $('#articleid').val(msg.data.id);

                }else{
                    $("#sure").val(0);
                    $("#asp").remove();
                    a.after("<span id='asp' style='color:red'>此书名书籍不存在</span>");
                    
                }
            }
        })
    })

</script>