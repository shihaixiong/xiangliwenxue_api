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
        width: 100px;
        height: 100px;
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
    .txt_name{
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
    .box-body{
        height: 300px;
    }
</style>
<div class="col-md-12">
    <div class="box box-info">
        <!-- form start -->
        <form action="/admin/recommend/updateRecommend_" method="post" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data" onsubmit="return check(this)">
            <div class="box-body">
                <div class="form-group">
                    <label for="name" class="col-sm-2  control-label">推荐位名称</label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                            <input type="text" name="name" id="name" class="form-control" value="<?php echo $data['name']; ?>">
                        </div>
                    </div>
                </div>
                 <!-- <div class="form-group">
                    <label for="count" class="col-sm-2  control-label">推荐位数量</label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                            <input type="text" name="count" id="count" class="form-control">
                        </div>
                    </div>
                </div> -->
                <div class="form-group">
                    <label for="type" class="col-sm-2  control-label">推荐位类型</label>
                    <div class="col-sm-8">
                        <select class="select2 form-control" id="type" name="type">
                            <?php if($data['type']==1){ ?>
                            <option value="1" selected>书籍</option>
                            <option value="2">图片</option>
                            <?php }else{ ?>
                            <option value="1">书籍</option>
                            <option value="2" selected>图片</option>
                            <?php } ?>
                            
                            
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="channel" class="col-sm-2  control-label">推荐位频道</label>
                    <div class="col-sm-8">
                        <select class="select2 form-control" id="channel" name="channel">
                           <!--  <option value="1" selected>男频</option>
                            <option value="2">女频</option>
                            <option value="3">漫画</option> -->
                            
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="vip" class="col-sm-2  control-label">仅vip可见</label>
                    <div class="col-sm-8">
                        <select class="select2 form-control" id="vip" name="vip">
                            <option value="0" @if($data['vip'] == 0) selected @endif >否</option>
                            <option value="1" @if($data['vip'] == 1) selected @endif >是</option>                            
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="style" class="col-sm-2  control-label">推荐位样式</label>
                    <div class="col-sm-8">
                        <select class="select2 form-control" id="style" name="style">
                            <!-- <option value="1" selected>3x2方格</option>
                            <option value="2">列表</option>
                            <option value="3">2x2方格</option>
                            <option value="4">横滑</option> -->
                            
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="displayorder" class="col-sm-2  control-label">排序</label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                            <input type="text" name="displayorder" id="displayorder" class="form-control" value="<?php echo $data['displayorder'];?>">
                        </div>
                    </div>
                </div>
                <input type="hidden" name="displayorder_old" id="displayorder_old" value="<?php echo $data['displayorder']; ?>" >
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
            <input type="hidden" name="id" id="id" value="<?php echo $data['id'];?>" />
        </form>
    </div>
</div>
<script type="text/javascript" src="{{ asset('admin_style/js/jquery.min.js') }}"></script>

<script type="text/javascript">
    $(function(){
        var type = $("#type").val();
        if(type == 1){
            if(<?php echo $data['style']; ?>==1){
                var str1 = "<option value='1' selected>4x2</option><option value='2'>列表</option><option value='3'>2x2</option><option value='4'>横滑</option>";
            }else if(<?php echo $data['style']; ?>==2){
                var str1 = "<option value='1'>4x2</option><option value='2' selected>列表</option><option value='3'>2x2</option><option value='4'>横滑</option>";
            }else if(<?php echo $data['style']; ?>==3){
                var str1 = "<option value='1' >4x2</option><option value='2'>列表</option><option value='3' selected>2x2</option><option value='4'>横滑</option>";
            }else if(<?php echo $data['style']; ?>==4){
                var str1 = "<option value='1' >4x2</option><option value='2'>列表</option><option value='3'>2x2</option><option value='4' selected>横滑</option>";
            }
            
           $("#style").append(str1);
        }else{
            if(<?php echo $data['style']; ?>==5){
                var str1 = "<option value='5' selected>单图</option><option value='6'>多图</option>";
            }else if(<?php echo $data['style']; ?>==6){
                var str1 = "<option value='5' >单图</option><option value='6' selected>多图</option>";
            }
           $("#style").append(str1);
        }

    })
    
    $("#type").change(function(){
        var type = $(this).val();
        $("#style").empty();
        typeChange(type);
    })
    
    function typeChange(type){
        if(type == 1){
            var str1 = "<option value='1' selected>4x2</option><option value='2'>列表</option><option value='3'>2x2</option><option value='4'>横滑</option>";
           $("#style").append(str1);
        }else{
            var str1 = "<option value='5' selected>单图</option><option value='6'>多图</option>";
           $("#style").append(str1);
        }
    }

    $.ajax({
        url: '<?php echo env('ADMIN_URL'); ?>book/bookChannel',
        success: function(msg){
            msg=JSON.parse(msg)
            
            var str = '<option value="0" >未选择</option>';
            for(var i =0; i<msg.length; i++){
                if( msg[i].id == <?php echo $data['channel']; ?>){
                    str+='<option value="' + msg[i].id + '" selected>' +  msg[i].channel + '</option>'
                }else{
                    str+='<option value="' + msg[i].id + '">' +  msg[i].channel + '</option>'
                }
                
            }
            $("#channel").append(str);
          
        }
    }) 

    function check(form){
        var name      = $("#name").val();
        var type       = $("#type").val();
        var channel     = $("#channel").val();
        var style     = $("#style").val();

        if(name=='' || type==0 || channel==0 || style==0 ){
            alert("请填写或下拉选择所有信息！不能为空！不能为未选择")
            return false;
        }else{
            
            return true;
        }
        
    }    

</script>