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
        <form action="/admin/down/addDown" method="post" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data" >
            <div class="box-body">
                
                <div class="form-group">
                    <label for="title" class="col-sm-2  control-label">备注</label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                            <input type="text" name="title" id="title" class="form-control" placeholder="请填写备注">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="note" class="col-sm-2  control-label">更新说明</label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                            <input type="text" name="note" id="note" class="form-control" placeholder="请输入更新说明">
                        </div>
                    </div>
                </div>

                 <div class="form-group">
                    <label for="version" class="col-sm-2  control-label">版本号</label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                            <input type="text" name="version" id="version" class="form-control" maxlength="150" value="1">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="type" class="col-sm-2  control-label">更新方式</label>
                    <div class="col-sm-8">
                        <select class="select2 form-control" id="type" name="type">
                            <option value="1" selected>普通更新</option>
                            <option value="2">强制更新</option>
                            
                            
                        </select>
                    </div>
                </div>
                
               <div class="form-group">
                    <label for="zhu" class="col-sm-2 control-label">apk文件上传</label>
                    <div class="col-sm-8">
                            <input type="file" name="apk" >
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
