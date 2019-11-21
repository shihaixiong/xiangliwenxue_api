<link rel="stylesheet" type="text/css" href="{{ asset('admin_style/css/dataTables.bootstrap.css') }}">
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">  章节列表</h3>
        <div class="box-tools">
            <div class="pull-right" style="margin-right: 10px">
                <a class="btn action-btn btn-success" id="create"><i class="fa fa-plus"></i>&nbsp;新增</a>
            </div>
        </div>
    </div>
    <div class="box-body table-responsive no-padding">
        <table class="table table-hover" id="example">
            <thead>
                <tr>
                    <th>id</th>
                    <th>标题</th>
                    <th>章节字数</th>
                    <th>是否付费</th>
                    <th>排序</th>
                    <th>新增时间</th>
                    <th>操作</th>
                </tr>
            </thead>

        </table>
    </div>
</div>
<script type="text/javascript" src="{{ asset('admin_style/js/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('admin_style/js/dataTables.bootstrap.js') }}"></script>
<script type="text/javascript">
    var myid = window.location.search;
    myid = myid.substring(myid.lastIndexOf('=')+1, myid.length);
    
    $(function () {
        $('#example').dataTable({
            "searching": false,
            "bLengthChange": false,
            "ajax": {
                url:"chapterGetIndex?book_id="+myid,
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
                    "data": "subhead",
                    "render": function (data, subhead, row, meta) {
                        var subhead = "" + row.subhead + "";
                        return subhead;
                    }
                },
                {
                    "targets": 2,
                    "data": "word_count",
                    "render": function (data, word_count, row, meta) {
                        var word_count = "" + row.word_count + "";
                        return word_count;
                    }
                },
                {
                    "targets": 3,
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
                    "targets": 4,
                    "data": "displayorder",
                    "render": function (data, displayorder, row, meta) {
                        var displayorder = "" + row.displayorder + "";
                        
                        return displayorder;
                    }
                },
                {
                    "targets": 5,
                    "data": "created_at",
                    "render": function (data, created_at, row, meta) {
                        var created_at = "" + row.created_at + "";
                        return created_at;
                    }
                },
                {
                    "targets": 6,
                    "data": "",
                    "render": function (data, type, row, meta) {


                        var s='<button type="button" class="btn btn-xs btn-warning edit" id="'+row.id+'">编辑</button>&nbsp;&nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-xs bg-red del" id="'+row.id+'">删除</button>';

                        
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
        // 编辑
        $("#example").on('click','.edit',function(){
        	var that= $(this);
            var id = that.attr('id');
            
            location.href="editChapter?id="+id+"&book_id="+myid;
        })
        // 删除
        $("#example").on('click','.del',function(){
            var that= $(this);
            var id = that.attr('id');
            
            location.href="delChapter?id="+id+"&book_id="+myid;
        })
        //新增
        $("#create").click(function(){
            location.href="createChapter?book_id="+myid;
        })
        
    })
</script>