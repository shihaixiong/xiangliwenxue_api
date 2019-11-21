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
        <form action="/admin/recommend/updShelfRec" method="post" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data" onsubmit="return check(this)">
            <div class="box-body">
                <div class="form-group">
                    <label for="articleid" class="col-sm-2  control-label">推荐位名称</label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                            <input type="text" name="name" id="name" class="form-control" value='{{$rec[0]['name'] ?? ''}}'>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="articleid" class="col-sm-2  control-label">书籍编号</label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                            <input type="text" name="articleid[]" id="articleid" class="form-control" value='{{$data[0]['articleid'] ?? ''}}'>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="articleid" class="col-sm-2  control-label"></label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                            <input type="text" name="articleid[]" id="articleid" class="form-control" value='{{$data[1]['articleid'] ?? ''}}'>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="articleid" class="col-sm-2  control-label"></label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                            <input type="text" name="articleid[]" id="articleid" class="form-control" value='{{$data[2]['articleid'] ?? ''}}'>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="articleid" class="col-sm-2  control-label"></label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                            <input type="text" name="articleid[]" id="articleid" class="form-control" value='{{$data[3]['articleid'] ?? ''}}'>
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
            <input type="hidden" name="rid" value="99999" />
        </form>
    </div>
</div>

<div class="col-md-12">
    <div class="box box-info">
        <!-- form start -->
        <form action="/admin/recommend/updShelfRec" method="post" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data" onsubmit="return check(this)">
            <div class="box-body">
                 <div class="form-group">
                    <label for="articleid" class="col-sm-2  control-label">vip推荐位名称</label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                            <input type="text" name="name" id="name" class="form-control" value='{{$rec[1]['name'] ?? ''}}'>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="articleid" class="col-sm-2  control-label">vip书籍编号</label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                            <input type="text" name="articleid[]" id="articleid" class="form-control" value='{{$vipData[0]['articleid'] ?? ''}}'>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="articleid" class="col-sm-2  control-label"></label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                            <input type="text" name="articleid[]" id="articleid" class="form-control" value='{{$vipData[1]['articleid'] ?? ''}}'>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="articleid" class="col-sm-2  control-label"></label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                            <input type="text" name="articleid[]" id="articleid" class="form-control" value='{{$vipData[2]['articleid'] ?? ''}}'>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="articleid" class="col-sm-2  control-label"></label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                            <input type="text" name="articleid[]" id="articleid" class="form-control" value='{{$vipData[3]['articleid'] ?? ''}}'>
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
            <input type="hidden" name="rid" value="100000" />
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
                    
                }
            }
        }) 
    });
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
        
    }
</script>