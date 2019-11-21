<link rel="stylesheet" type="text/css" href="{{ asset('admin_style/css/dataTables.bootstrap.css') }}">
@foreach($data as $k=>$v)
<div class="box">
    <div class="box-header with-border">
        @if($k == 'today')
        <h3 class="box-title">今日充值  </h3><span id='time'></span>
        @endif
        @if($k == 'yesterday')
        <h3 class="box-title">昨日充值 </h3>
        @endif
        @if($k == 'month')
        <h3 class="box-title">当月充值 </h3>
        @endif
        @if($k == 'all')
        <h3 class="box-title">累计充值 </h3>
        @endif
        <div class="box-tools">
           
        </div>
    </div>
    <div class="box-body table-responsive no-padding">
        <table class="table table-hover" id="example">
            <thead>
                <tr>
                    <th>充值金额</th>
                    <th>充值笔数</th>
                    <th>完成笔数</th>
                    <th>充值完成率</th>
                </tr>
                @if($k == 'today') 
                <tr>
                    <td class='money'>{{$v['money']}}元</td>
                    <td class='num'>{{$v['num']}}笔</td>
                    <td class='fin'>{{$v['fin']}}笔</td>
                    <td class='svg'>{{round((empty($v['num']) ? 0 : $v['fin']/$v['num'])*100,2)}}%</td>
                </tr>
                @else
                <tr>
                    <td>{{$v['money']}}元</td>
                    <td>{{$v['num']}}笔</td>
                    <td>{{$v['fin']}}笔</td>
                    <td>{{round((empty($v['num']) ? 0 : $v['fin']/$v['num'])*100,2)}}%</td>
                </tr>
                @endif

            </thead>

        </table>
    </div>
</div>
@endforeach
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
