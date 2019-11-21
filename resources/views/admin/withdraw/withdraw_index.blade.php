<link rel="stylesheet" type="text/css" href="{{ asset('admin_style/css/dataTables.bootstrap.css') }}">
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">提现列表</h3>
        <div class="box-tools">
           
        </div>
    </div>
    <div class="box-body table-responsive no-padding">
        <table class="table table-hover" id="example">
            <thead>
                <tr>
                    <th>id</th>
                    <th>用户id</th>
                    <th>提现金额</th>
                    <th>微信账号</th>
                    <th>申请时间</th>
                    <th>申请状态</th>
                    <th>完成时间</th>
                    <th>操作</th>
                </tr>
            </thead>

        </table>
    </div>
</div>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    ×
                </button>
                <h4 class="modal-title" id="myModalLabel">
                    增加物流信息
                </h4>
            </div>
            <div class="modal-body">
                <textarea class="form-control" placeholder="物流信息..." id="reason"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭
                </button>
                <button type="button" class="btn btn-primary" data-dismiss="modal" id="btn">
                    提交更改
                </button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal -->
</div>
<script type="text/javascript" src="{{ asset('admin_style/js/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('admin_style/js/dataTables.bootstrap.js') }}"></script>
<script type="text/javascript">
   
    
    $(function () {
        $('#example').dataTable({
            "searching": false,
            "bLengthChange": false,
            "ajax": {
                url:"withdrawGetIndex",
                type: 'GET',
            },
            "paging": true,
            "renderer": "bootstrap",
            "columnDefs":[
                {
                    "targets": 0,
                    "data": "id",
                    "render": function (data, id, row, meta) {
                        var id = "" + row.id + "";
                        return id;
                    }
                },
                {
                    "targets": 1,
                    "data": "userid",
                    "render": function (data, uid, row, meta) {
                        var uid = "" + row.userid + "";
                        return uid;
                    }
                },
                {
                    "targets": 2,
                    "data": "money",
                    "render": function (data, money, row, meta) {
                        var money = "" + row.money + "";
                        return money;
                    }
                },
                {
                    "targets": 3,
                    "data": "wechat_id",
                    "render": function (data, wechat_id, row, meta) {
                       var wechat_id = "" + row.wechat_id + "";
                       return wechat_id;
                    }
                },
                {
                    "targets": 4,
                    "data": "created_at",
                    "render": function (data, created_at, row, meta) {
                        var created_at = "" + row.created_at + "";
                        return created_at;
                    }
                },
                {
                    "targets": 5,
                    "data": "status",
                    "render": function (data, status, row, meta) {
                        var status = "" + row.status + "";
                        if(status==0) return "申请中";
                        if(status==1) return "已通过";
                        if(status==2) return "驳回";
                    }
                },
                {
                    "targets": 6,
                    "data": "updated_at",
                    "render": function (data, updated_at, row, meta) {
                        var updated_at = "" + row.updated_at + "";
                        return updated_at;
                    }
                },
            
                {
                    "targets": 7,
                    "data": "",
                    "render": function (data, type, row, meta) {
                        var s='<button type="button" class="btn btn-xs btn-warning liu" id="'+row.id+'">通过</button>&nbsp;&nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-xs btn-warning no" id="'+row.id+'">驳回</button>&nbsp;&nbsp;&nbsp;&nbsp;';
                        if(row.status != 0) s = '';
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
        $("#example").on('click','.liu',function(){
            // $("#reason").val('')
            var that= $(this);
            var id = that.attr('id');
            var prev_td=that.parent().prev().prev();

            // $("#myModal").modal("toggle");
            // $("#btn").click(function(){
            //     var content = $("#reason").val();
            //     if(content==""){
            //         alert("不能为空")
            //     }else{
                    $.ajax({
                        url: 'updateStatus',
                        data: {
                            id: id,
                        },
                        success: function(msg){
                            if(msg == '1'){
                                alert("成功")
                                prev_td.html("已通过")
                            }else{
                                alert("失败")
                            }
                        }
                    })  
            //     }
                
            // })
            
        })

        $("#example").on('click','.no',function(){
            // $("#reason").val('')
            var that= $(this);
            var id = that.attr('id');
            var prev_td=that.parent().prev().prev();

            // $("#myModal").modal("toggle");
            // $("#btn").click(function(){
            //     var content = $("#reason").val();
            //     if(content==""){
            //         alert("不能为空")
            //     }else{
                    $.ajax({
                        url: 'updateStatusNo',
                        data: {
                            id: id,
                        },
                        success: function(msg){
                            if(msg == '1'){
                                alert("成功")
                                prev_td.html("已驳回")
                            }else{
                                alert("失败")
                            }
                        }
                    })  
            //     }
                
            // })
            
        })
        // 查看
        // $("#example").on('click','.watch',function(){
        // 	var that= $(this);
        //     var id = that.attr('id');
            
        //     location.href="chapterIndex?book_id="+id;
        // })
        // // 编辑
        // $("#example").on('click','.edit',function(){
        // 	var that= $(this);
        //     var id = that.attr('id');
            
        //     location.href="editBook?id="+id;
        // })
        // // 删除
        // $("#example").on('click','.del',function(){
        //     var that= $(this);
        //     var id = that.attr('id');
            
        //     location.href="delBook?id="+id;
        //})
        
    })
</script>