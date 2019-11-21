<link rel="stylesheet" type="text/css" href="{{ asset('admin_style/css/dataTables.bootstrap.css') }}">
<div class="box">
    <div class="box-header with-border">
        <form action='/admin/statistics/composite' method='get'>        <h3 class="box-title">综合统计 <input type="month" name="month"></h3>
        <button type="submit" class="btn btn-warning">确定</button>
        </form>

        <div class="box-tools">
           
        </div>
    </div>
    <div class="box-body table-responsive no-padding">
        <table class="table table-hover" id="example">
            <thead>
                <tr>
                    <th>日期</th>
                    <th>签到次数</th>
                    <th>分享次数</th>
                    <th>评论次数</th>
                    <th>充值金额</th>
                    <th>订阅数量</th>
                    <th>注册用户数</th>
                    <th>积分数量</th>
                </tr>
                @foreach ($data as $k=>$v)
                <tr>
                    <td>{{$v['date']}}</td>
                    <td>{{$v['sign_num']}}</td>
                    <td>{{$v['share_num']}}</td>
                    <td>{{$v['comment_num']}}</td>
                    <td>{{$v['pay_num']}}</td>
                    <td>{{$v['paid_num']}}</td>
                    <td>{{$v['reg_num']}}</td>
                    <td>{{$v['integral_num']}}</td>
                </tr>
                @endforeach
            </thead>

        </table>
    </div>
</div>
