<link rel="stylesheet" type="text/css" href="{{ asset('admin_style/css/dataTables.bootstrap.css') }}">
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">推荐位列表</h3>
        <div class="box-tools">
            <div class="pull-right" style="margin-right: 10px">
                <a class="btn action-btn btn-success" href="createRecommend"><i class="fa fa-plus"></i>&nbsp;新增</a>
            </div>
        </div>
    </div>
    <div class="box-body table-responsive no-padding">
        <table class="table table-hover" id="example">
            <thead>
                <tr>
                    <!-- <th>id</th> -->
                    <th>推荐位名称</th>
                    <!-- <th>推荐位数量</th> -->
                    <th>推荐位类型</th>
                    <th>推荐位频道</th>
                    <th>推荐位样式</th>
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
                url:"recommendGetIndex",
                type: 'GET',
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
                    "data": "name",
                    "render": function (data, type, row, meta) {
                        var name = "" + row.name + "";
                        return name;
                    }
                },
                // {
                //     "targets": 2,
                //     "data": "count",
                //     "render": function (data, count, row, meta) {
                //         var count = "" + row.count + "";
                //         return count;
                //     }
                // },
                {
                    "targets": 1,
                    "data": "type",
                    "render": function (data, type, row, meta) {
                        var type = "" + row.type + "";
                        var type_str = '';
                        if(type==1){
                            type_str = "普通";
                        }else if(type==2){
                            type_str = "图片";
                        }
                        return type_str;
                    }
                },
                {
                    "targets": 2,
                    "data": "channel",
                    "render": function (data, channel, row, meta) {
                        var channel = "" + row.channel + "";
                        var channel_str = '';
                        if(channel==1){
                            channel_str = "男频";
                        }else if(channel==2){
                            channel_str = "女频";
                        }
                        else if(channel==3){
                            channel_str = "漫画";
                        }
                        return channel_str;
                    }
                },
                {
                    "targets": 3,
                    "data": "style",
                    "render": function (data, style, row, meta) {
                        var style = "" + row.style + "";
                        var style_str = '';
                        if(style==1){
                            style_str = "4x2";
                        }else if(style==2){
                            style_str = "列表";
                        }
                        else if(style==3){
                            style_str = "2x2";
                        }
                        else if(style==4){
                            style_str = "横滑";
                        }
                        else if(style==5){
                            style_str = "单图";
                        }
                        else if(style==6){
                            style_str = "多图";
                        }
                        return style_str;
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
                    "data": "",
                    "render": function (data, type, row, meta) {


                        var s='<button type="button" class="btn btn-xs btn-warning watch" rec_id="'+row.id+'">查看详情</button>&nbsp;&nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-xs btn-warning edit" rec_id="'+row.id+'">编辑</button>&nbsp;&nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-xs bg-red del" rec_id="'+row.id+'">删除</button>';

                        
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
            var rec_id = that.attr('rec_id');
            
            location.href="recommendInfoIndex?rec_id="+rec_id;
        })
        // 编辑
        $("#example").on('click','.edit',function(){
        	var that= $(this);
            var rec_id = that.attr('rec_id');
            
            location.href="editRecommend?rec_id="+rec_id;
        })
        // 删除
        $("#example").on('click','.del',function(){
            var that= $(this);
            var rec_id = that.attr('rec_id');
            
            location.href="delRecommend?rec_id="+rec_id;
        })
        
    })
</script>