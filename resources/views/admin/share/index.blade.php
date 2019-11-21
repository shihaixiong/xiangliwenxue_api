<link rel="stylesheet" type="text/css" href="{{ asset('admin_style/css/dataTables.bootstrap.css') }}">
<div class="box">
    <div class="box-header with-border">
        <form action='/admin/share/index' method='get'>        <h3 class="box-title">推广管理   <a href="/admin/share/add" class="btn btn-warning">新建推广链接</a>
            书名
        <input type="text" name="book_name">
    <button href="/admin/share/add" class="btn btn-warning">搜索</button>
        </form>

        <div class="box-tools">
        </div>
    </div>
    <div class="box-body table-responsive no-padding">
        <table class="table table-hover" id="example">
            <thead>
                <tr>
                    <th>推广标题</th>
                    <th>推广链接</th>
                    <th>书籍名称</th>
                    <th>点击pv(总)</th>
                    <th>点击下载pv(总)</th>
                    <th>添加时间</th>
                    <th>操作</th>
                </tr>

            </thead>
            @foreach($data as $k=>$v) 
                <tr>
                    <td>{{$v->title}}</td>
                    <td><?php echo $webUrl;?>spread/index?id={{$v->id}}</td>
                    <td>{{$v->book_name}}</td>
                    <td>{{$v->view_num}}</td>
                    <td>{{$v->down_num}}</td>
                    <td>{{date("Y-m-d H:i:s",$v->add_time)}}</td>
                    <td><a href="/admin/share/count?id={{$v->id}}">日统计</a>&nbsp;&nbsp;<a href="/admin/share/upd?id={{$v->id}}">修改</a>&nbsp;&nbsp;<a href="/admin/share/del?id={{$v->id}}" onclick="if(confirm('确定删除?')==false)return false;">删除</a></td>
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