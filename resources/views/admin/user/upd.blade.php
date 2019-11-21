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
        <form action="/admin/user/updDo" method="post" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data" onsubmit="return check(this)">
            <div class="box-body">
                <div class="form-group">
                    <label for="id" class="col-sm-2  control-label">用户ID</label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            {{$data['id']}}
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="id" class="col-sm-2  control-label">阅读币</label>
                    <div class="col-sm-8">
                         <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                            <input type="text" name="remain" id="remain" class="form-control"   value="{{$data['remain']}}">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="id" class="col-sm-2  control-label">积分</label>
                    <div class="col-sm-8">
                         <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                            <input type="text" name="integral" id="integral" class="form-control"  placeholder="请输入书名" value="{{$data['integral']}}">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="id" class="col-sm-2  control-label">余额</label>
                    <div class="col-sm-8">
                         <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                            <input type="text" name="money" id="money" class="form-control"  placeholder="请输入书名" value="{{$data['money']}}">
                        </div>
                    </div>
                </div>
                

               
            </div>

            <div class="box-footer">
                <div class="col-md-2"></div>
                <div class="col-md-8">
                    
                    <div class="btn-group">
                        <input type="hidden" name="id" value="{{$data['id']}}">
                        <button type="submit" class="btn btn-warning">确定</button>
                    </div>
                </div>
            </div>
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
        </form>
    </div>
</div>
