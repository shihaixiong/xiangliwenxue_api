<link rel="stylesheet" type="text/css" href="{{ asset('admin_style/css/dataTables.bootstrap.css') }}">
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">订单列表</h3>
        <div class="box-tools">
           
        </div>
    </div>
    <div class="box-body table-responsive no-padding">
        <table class="table table-hover" id="example">
            <thead>
                <tr>
                    <th>id</th>
                    <th>用户id</th>
                    <th>商品id</th>
                    <th>商品名称</th>
                    <th>订单号</th>
                    <th>收货地址</th>
                    <th>订单状态</th>
                    <th>价格</th>
                    <th>用户姓名</th>
                    <th>手机号</th>
                    <th>快递单号</th>
                    <th>添加时间</th>
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
                url:"orderGetIndex",
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
                    "data": "uid",
                    "render": function (data, uid, row, meta) {
                        var uid = "" + row.uid + "";
                        return uid;
                    }
                },
                {
                    "targets": 2,
                    "data": "goods_id",
                    "render": function (data, goods_id, row, meta) {
                        var goods_id = "" + row.goods_id + "";
                        return goods_id;
                    }
                },
                {
                    "targets": 3,
                    "data": "goods_name",
                    "render": function (data, goods_name, row, meta) {
                        var goods_name = "" + row.goods_name + "";
                        return goods_name;
                    }
                },
                {
                    "targets": 4,
                    "data": "order_num",
                    "render": function (data, order_num, row, meta) {
                        var order_num = "" + row.order_num + "";
                        return order_num;
                    }
                },
                {
                    "targets": 5,
                    "data": "address",
                    "render": function (data, address, row, meta) {
                        var address = "" + row.address + "";
                        return address;
                    }
                },
                {
                    "targets": 6,
                    "data": "order_status",
                    "render": function (data, order_status, row, meta) {
                        var order_status = "" + row.order_status + "";
                        if(order_status==0) return "0";
                        if(order_status==1) return "1";
                        
                        
                    }
                },
                {
                    "targets": 7,
                    "data": "price",
                    "render": function (data, price, row, meta) {
                        var price = "" + row.price + "";
                        return price;
                    }
                },
                {
                    "targets": 8,
                    "data": "name",
                    "render": function (data, name, row, meta) {
                        var name = "" + row.name + "";
                        return name;
                    }
                },
                {
                    "targets": 9,
                    "data": "tel",
                    "render": function (data, tel, row, meta) {
                        var tel = "" + row.tel + "";
                        return tel;
                    }
                },
                {
                    "targets": 10,
                    "data": "express_num",
                    "render": function (data, express_num, row, meta) {
                        var express_num = "" + row.express_num + "";
                        return express_num;
                    }
                },
                {
                    "targets": 11,
                    "data": "created_at",
                    "render": function (data, created_at, row, meta) {
                        var created_at = "" + row.created_at + "";
                        return created_at;
                    }
                },
                {
                    "targets": 12,
                    "data": "",
                    "render": function (data, type, row, meta) {

                        if(row.express_num == '') {
                            var s='<button type="button" class="btn btn-xs btn-warning liu" id="'+row.id+'">发货</button>&nbsp;&nbsp;&nbsp;&nbsp;';
                            return s;
                        } else {
                            return '';
                        }
                        
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
            $("#reason").val('')
            var that= $(this);
            var id = that.attr('id');
            var prev_td=that.parent().prev().prev();

            $("#myModal").modal("toggle");
            $("#btn").click(function(){
                var content = $("#reason").val();
                if(content==""){
                    alert("不能为空")
                }else{
                    $.ajax({
                        url: 'updateExpress',
                        data: {
                            id: id,
                            express: content
                        },
                        success: function(msg){
                            if(msg == '1'){
                                 that.remove();
                                prev_td.html(content)
                            }else{
                                alert("失败")
                            }
                        }
                    })  
                }
                
            })
            
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