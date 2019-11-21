<link rel="stylesheet" type="text/css" href="{{ asset('admin_style/css/dataTables.bootstrap.css') }}">
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">用户列表</h3>
        <div class="box-tools">
            <!-- <div class="pull-right" style="margin-right: 10px">
                <a class="btn action-btn btn-success" href="createBook"><i class="fa fa-plus"></i>&nbsp;新增</a>
            </div> -->
        </div>
    </div>
    <div class="box-body table-responsive no-padding">
        <table class="table table-hover" id="example">
            <thead>
                <tr>
                    <th>id</th>
                    <th>用户名</th>
                    <th>电话</th>
                    <th>头像</th>
                    <th>余额</th>
                    <th>操作</th>
                </tr>
            </thead>
            @foreach($data as $v)
            <tr>
                <td>{{$v->id}}</td>
                <td>{{$v->username}}</td>
                <td>{{$v->phone}}</td>
                <td><img src="/{{$v->image ?? 'images/moren.png'}}" style="width:100px; height: 100px"></td>
                <td>{{$v->remain}}</td>
                <td><a href="/admin/user/upd?id={{$v->id}}">查看</a></td>
            </tr>
            @endforeach
        </table>
    </div>
</div>

<div class="custom_page">
    @if(!empty($data))
        {{$data->appends([])->links()}}
    @endif
</div>