<link rel="stylesheet" type="text/css" href="{{ asset('admin_style/css/dataTables.bootstrap.css') }}">
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">下载管理 </h3>
        <div class="box-tools">
           <a href="/admin/down/add" class="btn btn-warning">添加</a>
        </div>
    </div>
    <div class="box-body table-responsive no-padding">
        <table class="table table-hover" id="example">
            <thead>
                <tr>
                    <th>标签</th>
                    <th>版本号</th>
                    <th>更新方式</th>
                    <th>更新提示</th>
                    <th>下载地址</th>
                    <th>操作</th>
                </tr>
                @foreach($data as $v)
                <tr>    
                    <td>{{$v->title}}</td>
                    <td>{{$v->version}}</td>
                    <td>{{$v->type}}</td>
                    <td>{{$v->note}}</td>
                    <td><a href="{{env('WEB_URL').'apk/'.$v->file}}">点击下载</a>{{env('WEB_URL').'apk/'.$v->file}}</td>
                    <td><a href="del?id={{$v->id}}">删除</a></td>
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
