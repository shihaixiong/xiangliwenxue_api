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
        <form action="/admin/recommend/addRecommendInfo" method="post" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data" onsubmit="return check(this)">
            <div class="box-body">
                <div class="form-group">
                    <label for="select_type" class="col-sm-2  control-label">查询书id</label>
                    <div class="col-sm-8">
                        <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                        <input type="text" name="title" id="title" class="form-control"  placeholder="请输入书名">
                    </div>
                </div>
                <?php if($type==2){ ?>
                <div class="form-group">
                    <label for="select_type" class="col-sm-2  control-label">推荐位类型</label>
                    <div class="col-sm-8">
                        <select class="select2 form-control" id="select_type" name="select_type">
                            <option value="articleid" selected>书籍编号</option>
                            <option value="link">链接</option>
                            
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="values" class="col-sm-2  control-label"></label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                            <input type="text" name="values" id="values" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="zhu" class="col-sm-2 control-label">上传图片</label>
                    <div class="col-sm-8">
                        <div class="my-prove">
                            <img src="" class="my-img">
                            <input type="file" name="pic" class="prove" id="zhu">
                        </div>
                    </div>
                </div>
                <?php }else if($type==1){ ?>
                <div class="form-group">
                    <label for="articleid" class="col-sm-2  control-label">书籍编号</label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                            <input type="text" name="articleid" id="articleid" class="form-control">
                        </div>
                    </div>
                </div>
                <?php } ?>
                <input type="hidden" name="rec_id" id="rec_id" value="<?php echo $rec_id; ?>" >
                
            </div>

            <div class="box-footer">
                <div class="col-md-2"></div>
                <div class="col-md-8">
                    
                    <div class="btn-group">
                        <button type="submit" class="btn btn-warning">确定</button>
                    </div>

                </div>
            </div>
            <input type="hidden" name="type" id="type" value="<?php echo $type; ?>" >
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />

            <input type="hidden" id="sure"  value="0">
        </form>
    </div>
</div>
<script type="text/javascript" src="{{ asset('admin_style/js/jquery.min.js') }}"></script>
<script type="text/javascript">
    $("#zhu").change(function(){
        var that= $("#zhu");
        var my_img = that.prev('img');
        var file = that[0].files[0];
        var reads= new FileReader();
        reads.readAsDataURL(file);
        reads.onload=function (e) {
            my_img.attr('src',e.target.result)
            my_img.show()
        };
    })
        
        //判断如果select是 书籍id 下面的input 必须是 数字

    $("#values").keyup(function(){
        $("#sure").val(0);
        var type = $("#select_type").val();
        if(type == "articleid"){
            var c=$(this);  
            if(/[^\d]/.test(c.val())){//替换非数字字符  
                var word_count=c.val().replace(/[^\d]/g,'');  
                $(this).val(word_count);  
            }   
        }
          
    })
    $("#articleid").keyup(function(){
        $("#sure").val(0);
        
            var c=$(this);  
            if(/[^\d]/.test(c.val())){//替换非数字字符  
                var word_count=c.val().replace(/[^\d]/g,'');  
                $(this).val(word_count);  
            }   
       
          
    })

    $("#articleid").blur(function(){
        var a = $(this)
        var value = $("#articleid").val();
        $.ajax({
            url: 'isBook',
            data:{
                id:value
            },
            success: function(msg){
                if(msg==1){
                    $("#asp").remove();
                    
                    $("#sure").val(1);
                }else{
                    $("#sure").val(0);
                    $("#asp").remove();
                     a.after("<span id='asp' style='color:red'>此id书籍不存在</span>");
                    //alert("此id书籍不存在")
                    
                }
            }
        }) 
    });
    $("#title").blur(function(){
        var title = $("#title").val();
        var a = $(this);
        $.ajax({
            url: 'getBookId',
            data:{
                title:title
            },
            success: function(msg){
                if(msg.code==1){
                     
                     $("#asp").remove();
                     $("#sure").val(1);
                     a.after("<span id='asp' style='color:blue'>书籍id:"+msg.data.id+"</span>");

                }else{
                    $("#sure").val(0);
                    $("#asp").remove();
                    a.after("<span id='asp' style='color:red'>此书名书籍不存在</span>");
                    
                }
            }
        })
    })
    $("#values").blur(function(){
        var type = $("#select_type").val();
        if(type == "articleid"){
            var a = $(this)
            var value = $("#values").val();
            $.ajax({
                url: 'isBook',
                data:{
                    id:value
                },
                success: function(msg){
                    if(msg==1){
                         
                         $("#asp").remove();
                         $("#sure").val(1);
                    }else{
                        $("#sure").val(0);
                        $("#asp").remove();
                        a.after("<span id='asp' style='color:red'>此id书籍不存在</span>");
                        
                    }
                }
            }) 
        }
    });
    $("#select_type").change(function(){
        $("#asp").remove();
    })
    function check(form){
        
        <?php if($type==2){ ?>
            var values      = $("#values").val();
            var pic = $(".my-img").attr('src');
            
            
            if(values=='' || pic=='' ){
                alert("请填写或下拉选择所有信息！不能为空！不能为未选择")
                return false;
            }else{
                if($("#select_type").val()=="articleid"){
                    if($("#sure").val()==0){
                        
                        return false;
                    }else{
                        return true;
                    }
                }else{
                    return true;
                }
                
            }
        <?php }else{ ?>
            
            var articleid      = $("#articleid").val();
            if(articleid==''){
                alert("请填写或下拉选择所有信息！不能为空！不能为未选择")
                return false;
            }else{
                if($("#sure").val()==0){
                   
                    return false;
                }else{
                    return true;
                }
                
            }
        <?php } ?>
        
    }   
</script>