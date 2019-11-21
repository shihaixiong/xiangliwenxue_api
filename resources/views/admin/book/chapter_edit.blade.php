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
        <form action="/admin/book/updateChapter" method="post" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data" onsubmit="return check(this)">
            <div class="box-body">
                <div class="form-group">
                    <label for="subhead" class="col-sm-2  control-label">章节标题</label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                            <input type="text" name="subhead" id="subhead" class="form-control" value="<?php echo $data['subhead']; ?>">
                        </div>
                    </div>
                </div>
                <!--  <div class="form-group">
                    <label for="word_count" class="col-sm-2  control-label">章节字数</label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                            <input type="text" name="word_count" id="word_count" class="form-control" value="<?php echo $data['word_count']; ?>">
                        </div>
                    </div>
                </div> -->
                <!-- <div class="form-group">
                    <label for="visible" class="col-sm-2  control-label">隐藏状态</label>
                    <div class="col-sm-8">
                        <select class="select2 form-control" id="visible" name="visible">
                            <?php if($data['visible']==0){ ?>
                            <option value="0" selected>隐藏</option>
                            <option value="1">正常</option>
                            <?php }else{ ?>
                            <option value="0">隐藏</option>
                            <option value="1" selected>正常</option>
                            <?php } ?>
                        </select>
                    </div>
                </div> -->
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
                    <label for="book_content" class="col-sm-2  control-label">文章内容</label>
                    <div class="col-sm-8">
                        <textarea style="border:0;border-radius:5px;background-color:rgba(241,241,241,.98);width: 85%;height: 500px;padding: 10px;resize: none;overflow-y:scroll" id ="book_content" name="book_content">
                            <?php echo $data['content']; ?>
                       
                        </textarea>
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
            <input type="hidden" name="book_id" value="<?php echo $data['book_id']; ?>">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
        </form>
    </div>
</div>
<script type="text/javascript" src="{{ asset('admin_style/js/jquery.min.js') }}"></script>

<script type="text/javascript">
    function check(form){
        var subhead      = $("#subhead").val();
        var book_content = $("#book_content").val();
      
        
        if(subhead=='' || book_content==''){
            alert("请填写所有信息！不能为空！")
            return false;
        }else{
            
            return true;
        }  
    }
</script>