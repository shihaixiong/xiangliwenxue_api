<link rel="stylesheet" type="text/css" href="{{ asset('admin_style/css/dataTables.bootstrap.css') }}">
<div class="box">
    <div class="box-header with-border">
        <form><h3 class="box-title">书籍列表  
                <input type="text" name="title" class='search' value="{{$search ?? ''}}">&nbsp;&nbsp;<button type="submit"  class="btn action-btn btn-success" >&nbsp;搜索</button></form></h3>
        <div class="box-tools">
            <div class="pull-right" style="margin-right: 10px">
                <a class="btn action-btn btn-success" href="createBook"><i class="fa fa-plus"></i>&nbsp;新增</a>
            </div>
        </div>
    </div>
    <div class="box-body table-responsive no-padding">
        <table class="table table-hover" id="example">
            <thead>
                <tr>
                    <th>id</th>
                    <th>图片</th>
                    <th>书名</th>
                    <!-- <th>简介</th> -->
                    <th>作者</th>
                    <th>分类</th>
                    <th>频道</th>
                    <th>是否完结</th>
                    <th>添加时间</th>
                    <th>是否隐藏</th>
                    <th>是否付费</th>
                    <th>点击量</th>
                    <th>操作</th>
                </tr>
            </thead>

        </table>
    </div>
</div>
<script type="text/javascript" src="{{ asset('admin_style/js/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('admin_style/js/dataTables.bootstrap.js') }}"></script>
<script type="text/javascript">
   
    
    $(function () {
        var title=$('.search').val();
        $('#example').dataTable({
            "searching": false,
            "bLengthChange": false,
            "ajax": {
                url:"bookGetIndex?title="+title,
                type: 'GET',
            },
            "paging": true,
            "renderer": "bootstrap",
            "columnDefs":[
                {
                    "targets": 0,
                    "data": "id",
                    "render": function (data, type, row, meta) {
                        var id = "" + row.id + "";
                        return id;
                    }
                },
                {
                    "targets": 1,
                    "data": "image",
                    "render": function (data, image, row, meta) {
                        var image = "" + row.image + "";
                        return "<img style='width:70px;height:70px;' src='<?php echo env('IMG_URL')?>"+image+"'>";
                    }
                },
                {
                    "targets": 2,
                    "data": "title",
                    "render": function (data, title, row, meta) {
                        var title = "" + row.title + "";
                        return title;
                    }
                },
                // {
                //     "targets": 3,
                //     "data": "desc",
                //     "render": function (data, desc, row, meta) {
                //         var desc = "" + row.desc + "";
                //         return desc;
                //     }
                // },
                {
                    "targets": 3,
                    "data": "author",
                    "render": function (data, author, row, meta) {
                        var author = "" + row.author + "";
                        return author;
                    }
                },
                {
                    "targets": 4,
                    "data": "sortid",
                    "render": function (data, sortid, row, meta) {
                        var sortid = "" + row.sortid + "";
                        if(sortid==0) return "未分类";
                        if(sortid==1) return "都市生活";
                        if(sortid==2) return "玄幻奇幻"; 
                        if(sortid==3) return "历史军事";
                        if(sortid==4) return "悬疑灵异"; 
                        if(sortid==5) return "仙侠武侠";
                        if(sortid==6) return "校园生活"; 
                        if(sortid==7) return "古代言情";
                        if(sortid==8) return "现代言情"; 
                        if(sortid==9) return "穿越架空"; 
                        if(sortid==10) return "校园生活"; 
                        if(sortid==11) return "幻想言情"; 
                    }
                },
                {
                    "targets": 5,
                    "data": "channel",
                    "render": function (data, channel, row, meta) {
                        var channel = "" + row.channel + "";
                        var channel_str = '';
                        if(channel==1){
                            channel_str = "男频";
                        }else if(channel==2){
                            channel_str = "女频";
                        }else if(channel==3){
                            channel_str = "漫画";
                        }
                        
                        return channel_str;
                    }
                },
                {
                    "targets": 6,
                    "data": "finish",
                    "render": function (data, finish, row, meta) {
                        var finish = "" + row.finish + "";
                        var finish_str = '';
                        if(finish==0){
                            finish_str = "未完结";
                        }else if(finish==1){
                            finish_str = "已完结";
                        }
                        
                        return finish_str;
                    }
                },
                {
                    "targets": 7,
                    "data": "created_at",
                    "render": function (data, created_at, row, meta) {
                        var created_at = "" + row.created_at + "";
                        return created_at;
                    }
                },
                {
                    "targets": 8,
                    "data": "visible",
                    "render": function (data, visible, row, meta) {
                        var visible = "" + row.visible + "";
                        var visible_str = '';
                        if(visible==0){
                            visible_str = "隐藏";
                        }else if(visible==1){
                            visible_str = "显示";
                        }
                        
                        return visible_str;
                    }
                },
                {
                    "targets": 9,
                    "data": "is_vip",
                    "render": function (data, is_vip, row, meta) {
                        var is_vip = "" + row.is_vip + "";
                        var is_vip_str = '';
                        if(is_vip==0){
                            is_vip_str = "否";
                        }else if(is_vip==1){
                            is_vip_str = "是";
                        }
                        
                        return is_vip_str;
                    }
                },
                {
                    "targets": 10,
                    "data": "clicks",
                    "render": function (data, clicks, row, meta) {
                        var clicks = "" + row.clicks + "";
                        return clicks;
                    }
                },
                {
                    "targets": 11,
                    "data": "",
                    "render": function (data, type, row, meta) {


                        var s='<button type="button" class="btn btn-xs btn-warning watch" id="'+row.id+'">查看章节</button>&nbsp;&nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-xs btn-warning edit" id="'+row.id+'">编辑</button>&nbsp;&nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-xs bg-red del" id="'+row.id+'">删除</button>&nbsp;&nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-xs bg-red export" id="'+row.id+'">导出txt</button>';

                        
                        return s;
                    }
                }
            ],
            "language": {
                "info": "显示 _START_ 到 _END_ 共 _TOTAL_ 条数据",
                "paginate": {//分页的样式内容。
                    "previous": "上一页",
                    "next": "下一页",
                    "first": "第一页",
                    "last": "最后"
                },
                "pagingType": "simple_numbers",
            }
        });
        // 查看
        $("#example").on('click','.watch',function(){
        	var that= $(this);
            var id = that.attr('id');
            
            location.href="chapterIndex?book_id="+id;
        })
        // 编辑
        $("#example").on('click','.edit',function(){
        	var that= $(this);
            var id = that.attr('id');
            
            location.href="editBook?id="+id;
        })
        // 删除
        $("#example").on('click','.del',function(){
            var that= $(this);
            var id = that.attr('id');
            
            location.href="delBook?id="+id;
        })

        // 删除
        $("#example").on('click','.export',function(){
            var that= $(this);
            var id = that.attr('id');
            
            location.href="export?articleid="+id;
        })
        
    })
</script>