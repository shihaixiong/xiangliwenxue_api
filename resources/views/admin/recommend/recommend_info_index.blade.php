<link rel="stylesheet" type="text/css" href="{{ asset('admin_style/css/dataTables.bootstrap.css') }}">
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">推荐位详情</h3>
        <div class="box-tools">
            <div class="pull-right" style="margin-right: 10px">
                <a class="btn action-btn btn-success" id="create" ><i class="fa fa-plus"></i>&nbsp;新增</a>
            </div>
        </div>
    </div>
    <div class="box-body table-responsive no-padding">
        <table class="table table-hover" id="example">
            <thead>
                <tr>
                    <!-- <th>id</th> -->
                    <th>书籍编号</th>
                    <th>书籍名称</th>
                    <th>推荐位类型</th>
                    <th>连接</th>
                    <th>图片</th>
                    <th>排序</th>
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
    //alert(myid)
    
    $(function () {
        $('#example').dataTable({
            "searching": false,
            "bLengthChange": false,
            "ajax": {
                url:"recommendInfoGetIndex?rec_id="+myid,
            },
            "paging": true,
            "renderer": "bootstrap",
            "columnDefs":[
                // {
                //     "targets": 0,
                //     "data": "id",
                //     "render": function (data, type, row, meta) {
                //         var id = "" + row.id + "";
                //         return id;
                //     }
                // },
                {
                    "targets": 0,
                    "data": "articleid",
                    "render": function (data, type, row, meta) {
                        var articleid = "" + row.articleid + "";
                        return articleid;
                    }
                },
                 {
                    "targets": 1,
                    "data": "title",
                    "render": function (data, type, row, meta) {
                        var title = "" + row.title + "";
                        return title;
                    }
                },
                {
                    "targets": 2,
                    "data": "type",
                    "render": function (data, type, row, meta) {
                        var type = "" + row.type + "";
                        var type_str = '';
                        if(type==1){
                            type_str = "书籍";
                        }else if(type==2){
                            type_str = "图片";
                        }
                        return type_str;
                    }
                },
                {
                    "targets": 3,
                    "data": "link",
                    "render": function (data, type, row, meta) {
                        var link = "" + row.link + "";
                        return link;
                    }
                },
                {
                    "targets": 4,
                    "data": "image",
                    "render": function (data, type, row, meta) {
                        var image = "" + row.image + "";
                        return "<img style='width:70px;height:70px;' src='<?php echo env('IMG_URL')?>"+image+"'>";
                    }
                },
                {
                    "targets": 5,
                    "data": "displayorder",
                    "render": function (data, type, row, meta) {
                        var displayorder = "" + row.displayorder + "";
                        return displayorder;
                    }
                },
                {
                    "targets": 6,
                    "data": "",
                    "render": function (data, type, row, meta) {


                        var s='<button type="button" class="btn btn-xs btn-warning hui" aid="'+row.id+'">编辑</button>&nbsp;&nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-xs bg-red del" aid="'+row.id+'">删除</button>';
                        
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
        $("#example").on('click','.hui',function(){
            var that= $(this);
            var aid = that.attr('aid');
            location.href="editRecommendInfo?id="+aid+"&rec_id="+myid;
        })

        // 删除
        $("#example").on('click','.del',function(){
            var that= $(this);
            var aid = that.attr('aid');
            location.href="delRecommendInfo?id="+aid+"&rec_id="+myid;
        })

        //新增
        $("#create").click(function(){
            location.href="createRecommendInfo?rec_id="+myid;
        })
        // // // 添加
        // $("#example").on('click','.add',function(){
        //     var that= $(this);
        //     var aid = that.attr('aid');
        //     location.href="createAssessment?id="+aid;
        // })
    })
</script>