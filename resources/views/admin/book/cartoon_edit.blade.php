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
        <form action="/admin/cartoon/updateBook" method="post" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data" onsubmit="return check(this)">
            <div class="box-body">
                <div class="form-group">
                    <label for="title" class="col-sm-2  control-label">书籍名称</label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                            <input type="text" name="title" id="title" class="form-control" value="<?php echo $data['title']; ?>">
                        </div>
                    </div>
                </div>
                 <div class="form-group">
                    <label for="desc" class="col-sm-2  control-label">书籍简介</label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                            <input type="text" name="desc" id="desc" class="form-control" value="<?php echo $data['desc']; ?>">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="author" class="col-sm-2  control-label">作者</label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                            <input type="text" name="author" id="author" class="form-control" value="<?php echo $data['author']; ?>">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="sortid" class="col-sm-2  control-label">分类</label>
                    <div class="col-sm-8">
                        <select class="select2 form-control" id="sortid" name="sortid">
<!-- 
                            <option value="0" selected>未分类</option>
                            <option value="1">言情</option>
                            <option value="2">校园</option>
                            <option value="3">玄幻</option>
                            <option value="4">恐怖</option>
                            <option value="5">悬疑</option>
                            <option value="6">社会</option>
                            <option value="7">战争</option>
                            <option value="8">自传</option> -->
                            
                            
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="keywords" class="col-sm-2  control-label">关键词</label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                            <input type="text" name="keywords" id="keywords" class="form-control" value="<?php echo $data['keywords']; ?>" >
                        </div>
                    </div>
                </div>
                <!-- <div class="form-group">
                    <label for="word_count" class="col-sm-2  control-label">总字数</label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                            <input type="text" name="word_count" id="word_count" class="form-control" value="<?php echo $data['word_count']; ?>">
                        </div>
                    </div>
                </div> -->
                <div class="form-group">
                    <label for="channel" class="col-sm-2  control-label">频道</label>
                    <div class="col-sm-8">
                        <select class="select2 form-control" id="channel" name="channel">
                            <!-- <option value="0" selected>未选择</option> -->
                            <!-- <option value="1">男频</option>
                            <option value="2">女频</option>
                            <option value="3">漫画</option> -->
                            
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="finish" class="col-sm-2  control-label">是否完结</label>
                    <div class="col-sm-8">
                        <select class="select2 form-control" id="finish" name="finish">
                            <?php if($data['finish']==0){ ?>
                            <option value="0" selected>未完结</option>
                            <option value="1">已完结</option>
                            <?php }else{ ?>
                            <option value="0">未完结</option>
                            <option value="1" selected>已完结</option>
                            <?php } ?>
                            
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="visible" class="col-sm-2  control-label">是否隐藏</label>
                    <div class="col-sm-8">
                        <select class="select2 form-control" id="visible" name="visible">
                            <?php if($data['visible']==0){ ?>
                            <option value="0" selected>隐藏</option>
                            <option value="1">显示</option>
                            <?php }else{ ?>
                            <option value="0">隐藏</option>
                            <option value="1" selected>显示</option>
                            <?php } ?>
                            
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="is_vip" class="col-sm-2  control-label">是否付费</label>
                    <div class="col-sm-8">
                        <select class="select2 form-control" id="is_vip" name="is_vip">
                            <?php if($data['is_vip']==0){ ?>
                            <option value="0" selected>否</option>
                            <option value="1">是</option>
                            <?php }else{ ?>
                            <option value="0">否</option>
                            <option value="1" selected>是</option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="price" class="col-sm-2  control-label">单章价格</label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                            <input type="text" name="price" id="price" class="form-control" value="<?php echo $data['price']; ?>">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="start_vip_num" class="col-sm-2  control-label">开始vip章节数</label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                            <input type="text" name="start_vip_num" id="start_vip_num" class="form-control" value="<?php echo $data['start_vip_num']; ?>">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="start_num" class="col-sm-2  control-label">推广开始章节</label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                            <input type="text" name="start_num" id="start_num" class="form-control" value="<?php echo $data['start_num'] ?? 1; ?>">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="end_num" class="col-sm-2  control-label">推广章节数量</label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                            <input type="text" name="end_num" id="end_num" class="form-control" value="<?php echo $data['end_num'] ?? 1; ?>">
                        </div>
                    </div>
                </div>
               <div class="form-group">
                    <label for="zhu" class="col-sm-2 control-label">上传封面</label>
                    <div class="col-sm-8">
                        <div class="my-prove">
                            <img src="<?php echo $data['image'];?>" class="my-img">
                            <input type="file" name="pic" class="prove" id="zhu">
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
            <input type="hidden" name="id" value="<?php echo $data['id']; ?>">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
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
        
    $.ajax({
        url: 'bookSort',
        success: function(msg){
            msg=JSON.parse(msg)
            
            var str = '<option value="0" >未选择</option>';
            for(var i =0; i<msg.length; i++){
                if( msg[i].id == <?php echo $data['sortid']; ?>){
                    str+='<option value="' + msg[i].id + '" selected>' +  msg[i].sort + '</option>'
                }else{
                    str+='<option value="' + msg[i].id + '">' +  msg[i].sort + '</option>'
                }
                
            }
            $("#sortid").append(str);
          
        }
    })

    $.ajax({
        url: 'bookChannel',
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
        var title      = $("#title").val();
        var desc       = $("#desc").val();
        var author     = $("#author").val();
        var sortid     = $("#sortid").val();
        var keywords   = $("#keywords").val();
        // var word_count = $("#word_count").val();
        var channel = $("#channel").val();
        // var finish = $("#finish").val();
        // var visible = $("#visible").val();
        // var is_vip = $("#is_vip").val();
        var pic = $("#my-img").attr('src');

        var re=/[，,]/g;
        if(re.test(keywords)){
            var n=keywords.match(re).length;
            if(n>2){
                alert("最多填写三个关键词，请用两个英文逗号隔开")
                return false;
            }
        }

        if(title=='' || desc=='' || author=='' || sortid==0  || word_count=='' || channel==0 || pic==''){
            alert("请填写或下拉选择所有信息！不能为空！不能为未选择")
            return false;
        }else{
            
            return true;
        }

        
    }

</script>