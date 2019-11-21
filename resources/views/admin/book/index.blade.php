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
    .width-45-left{
            width: 45%;
            float: left;
    }
    .width-45-right{
        width: 45%;
        float: right;
    }
    .box-body{
        height: 670px;
    }
</style>
<div class="col-md-12">
    <div class="box box-info">
        <!-- form start -->
        <form action="/admin/chaptersContent/index" method="post" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data" onsubmit="return check(this)">
            <div class="box-body">
                <div class="fields-group">
                   
                    <div class="form-group">
                       <textarea style="border:0;border-radius:5px;background-color:rgba(241,241,241,.98);width: 85%;height: 500px;padding: 10px;resize: none;overflow-y:scroll" id ="book_content" name="content">

                           
                       </textarea>
                       <input type="hidden" name="chapter_id" id="chapter_id" value="">
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


    var myid = window.location.search;
    myid = myid.substring(myid.lastIndexOf('=')+1, myid.length);

    
    $.ajax({
        url: 'getBookContent',
        data: {
            id: myid
        },
        dataType:'json',
        success: function(msg){

            // console.log(msg);
             console.log(msg.id);


            
           $("#book_content").val(msg.content)
           $("#chapter_id").val(msg.id)

        }
    })




</script>