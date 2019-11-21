<link rel="stylesheet" type="text/css" href="{{ asset('admin_style/css/dataTables.bootstrap.css') }}">
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">书籍消费 </h3>
        <div class="box-tools">
           
        </div>
    </div>
    <div class="box-body table-responsive no-padding">
        <form action="/admin/config/update" method="post" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data" onsubmit="return check(this)">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" />

        <table class="table table-hover" id="example">
            <thead>
                <tr>
                    <th>签到赠送书币</th>
                    
                </tr>
                <tr>    
                    <td><input type="text" name="sign_coin" value="{{$data['sign_coin'] ?? 0}}"></td>
                </tr>
                 <tr>
                    <th>苹果充值金额</th>
                    
                </tr>
                @foreach($data['pay_info'] as $num=>$v) 
                    <tr class="pay_info">    
                        <td>
                            id<input type="text" name="pay_info[{{$num}}][id]" value="{{$v['id'] ?? 0}}" style="width:40px;">
                            金额<input type="text" name="pay_info[{{$num}}][money]" value="{{$v['money'] ?? 0}}" style="width:40px;">元
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            书币<input type="text" name="pay_info[{{$num}}][coin]" value="{{$v['coin'] ?? 0}}" style="width:50px;">
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            奖励<input type="text" name="pay_info[{{$num}}][reward]" value="{{$v['reward'] ?? 0}}" style="width:50px;">书币
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            描述<input type="text" name="pay_info[{{$num}}][desc]" value="{{$v['desc'] ?? ''}}">
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <a href="javascript:void(0)" class="del_pay">删除</a>
                        </td>
                    </tr>
                @endforeach
                <tr class='new_pay'></tr>
                <tr>
                    <td><a href="javascript:void(0)"  class="add_pay_info">增加</a></td>
                </tr>
        </table>
        <table class="table table-hover" >
            <tr>
                <th>任务中心</th>
            </tr>
                    @foreach($task as $k=>$v)
                    <tr>
                        <td>
                            任务名称: {{$v['task_name']}}
                        </td>
                        <td>积分<input type="text" name="task[{{$v['id']}}]" value="{{$v['reward_integral'] ?? 0}}" style="width:50px;"></td>
                    </tr>
                    @endforeach
                    
            </thead>

        </table>
        <table class="table table-hover" >
            <tr>
                <th>兑换比例</th>
            </tr>
                    <tr>
                        <td>
                            余额兑换积分
                        </td>
                        <td>1:<input type="text" name="money_to_integral" value="{{$data['money_to_integral'] ?? 0}}" style="width:50px;"></td>
                    </tr><tr>
                        <td>
                            余额兑换阅读币
                        </td>
                        <td>1:<input type="text" name="money_to_remain" value="{{$data['money_to_remain'] ?? 0}}" style="width:50px;"></td>
                    </tr>
                    <tr>
                        <td>
                            积分兑换阅读币
                        </td>
                        <td>1:<input type="text" name="integral_to_remain" value="{{$data['integral_to_remain'] ?? 0}}" style="width:50px;"></td>
                    </tr>
            </thead>

        </table>
        <table class="table table-hover" >
            <tr>
                <th>分销比例</th>
            </tr>
                    <tr>
                        <td>
                            一级奖励
                        </td>
                        <td><input type="text" name="one_ratio" value="{{$data['one_ratio'] ?? 0}}" style="width:50px;">%</td>
                    </tr><tr>
                        <td>
                            二级奖励
                        </td>
                        <td><input type="text" name="two_ratio" value="{{$data['two_ratio'] ?? 0}}" style="width:50px;">%</td>
                    </tr>
                    <tr>
                        <td><div class="btn-group">
                        <button type="submit" class="btn btn-warning">确定</button>
                    </div></td>
                    </tr>
            </thead>

        </table>
    </form>
    </div>
</div>


<script src="/admin_style/js/jquery.min.js"></script>

<script type="text/javascript">
    var num = {{$num}};
    $(".add_pay_info").on("click",function(){
        num = num+1;
        var info = $('.pay_info_html').html();
        $('.new_pay').before("<tr class='pay_info'><td>"+
            'id<input type="text" name="pay_info['+ num+'][id]" value="" style="width:40px;">'+
            '金额<input type="text" name="pay_info['+ num+'][money]" value="" style="width:40px;">元'+
            "&nbsp;&nbsp;&nbsp;&nbsp;"+
            '书币<input type="text" name="pay_info['+ num+'][coin]" value="" style="width:50px;">'+
            "&nbsp;&nbsp;&nbsp;&nbsp;"+
            '奖励<input type="text" name="pay_info['+ num+'][reward]" value="" style="width:50px;">书币'+
            "&nbsp;&nbsp;&nbsp;&nbsp;"+
            '描述<input type="text" name="pay_info['+ num+'][desc]" value="">'+
            "&nbsp;&nbsp;&nbsp;&nbsp;"+
            '<a href="javascript:void(0)" class="del_pay">删除</a>'+
           ' <script type="text/javascript">'+
                           '$(".del_pay").on("click", function(){'+
                               '$(this).parents(".pay_info").remove();'+
                           '})'+
                       '<\/script>'
                   +"</tr></td>");

    })

    $(".del_pay").on("click", function(){
        $(this).parents('.pay_info').remove();
    })
</script>
