<link rel="stylesheet" type="text/css" href="{{ asset('admin_style/css/dataTables.bootstrap.css') }}">
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">书籍消费 </h3>
        <div class="box-tools">
           
        </div>
    </div>
    <div class="box-body table-responsive no-padding">
        <table class="table table-hover" id="example">
            <thead>
                <tr>
                    <th>用户id</th>
                    <th>消费详情</th>
                    <th>消费金额</th>
                    <th>消费时间</th>
                </tr>
                @foreach($data as $v)
                <tr>    
                    <td>{{$v->userid}}</td><td>{{$v->subject}}</td> <td>{{$v->price}}</td><td>{{$v->created_at}}</td>
                </tr>
                @endforeach

            </thead>

        </table>
    </div>
</div>

<div class="custom_page">
    @if(!empty($data))
        {{$data->appends([])->links()}}
    @endif
</div>
<script src="/admin_style/js/jquery.min.js"></script>
<script type="text/javascript">
    var flag = 1;  
    var i = 3;  
    function countDown() {  
        i = i - 1;  
        $("#time").html(i+"秒后刷新");  
        if (i == 0) { 
            $.ajax({
                url: 'getTodayPay',
                success: function(msg){
                    $('.money').html(msg.data.money);
                    $('.num').html(msg.data.num);
                    $('.fin').html(msg.data.fin);
                    if(msg.data.num == 0) {
                        var avg = 0;
                    }else{
                        var avg = msg.data.fin / msg.data.num * 100;
                    }

                    avg.toFixed(2);
                    $('.avg').html(avg+'%');
                  
                }
            })  
            flag = 1;  
            i = 60;  
        }  
        setTimeout('countDown()',1000);  
    }

    countDown();

</script>
