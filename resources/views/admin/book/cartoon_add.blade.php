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
        <form action="/admin/cartoon/addBook" method="post" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data" onsubmit="return check(this)">
            <div class="box-body">
                <div class="fields-group">
                   
                    <div class="form-group">
                        <label for="zhu" class="col-sm-2 control-label">上传漫画TXT</label>
                        <div class="col-sm-8">
                            <div class="my-prove">
                                <div class ='txt_name'></div>
                                <input type="file" name="files" class="prove" id="zhu"  accept=".txt">
                               <!--  <input type="file" name="txt1"> -->
                            </div>
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

<script type="text/javascript">
    // $(".btn").click(function(){
    //     alert(1)
    // })
    $(".prove").change(function(){
        var uploadfile = $(".prove").val();
        var fileName= getFileName(uploadfile);

        $(".txt_name").html(fileName);
        $(".txt_name").show()
        //alert("文件名是："+fileName);
    })
    function getFileName(file){//通过第一种方式获取文件名
        var pos=file.lastIndexOf("\\");//查找最后一个\的位置
        return file.substring(pos+1); //截取最后一个\位置到字符长度，也就是截取文件名 
    }

</script>
