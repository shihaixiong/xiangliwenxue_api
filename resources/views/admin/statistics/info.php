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
                    <td>{{$v->userid}}</td>
                    <td>{{$v->subject}}</td> 
                    <td>{{$v->price}}</td>
                    <td>{{$v->created_at}}</td>
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

