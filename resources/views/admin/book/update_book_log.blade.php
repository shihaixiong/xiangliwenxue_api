<link rel="stylesheet" type="text/css" href="{{ asset('admin_style/css/dataTables.bootstrap.css') }}">
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">  章节列表</h3>
        <div class="box-tools">
            <div class="pull-right" style="margin-right: 10px">
                <a class="btn action-btn btn-success" id="create"><i class="fa fa-plus"></i>&nbsp;新增</a>
            </div>
        </div>
    </div>
    <div class="box-body table-responsive no-padding">
        <table class="table table-hover" id="example">
            <thead>
                <tr>
                    <th>文件名</th>
                    <th>处理结果</th>
                    <th>上传时间</th>
                    <th>状态</th>
                </tr>
            </thead>
            @foreach($data as $v)
            <tr>
                <td>{{$v->title}}</td>
                <td>{{$v->log}}</td>
                <td>{{$v->created_at}}</td>
                <td> 
                    @if($v->status == 0) 等待处理中... @endif 
                    @if($v->status == 1) 处理中... @endif
                    @if($v->status == 2) 完成 @endif
                </td>
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
