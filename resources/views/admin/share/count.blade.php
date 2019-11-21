<link rel="stylesheet" type="text/css" href="{{ asset('admin_style/css/dataTables.bootstrap.css') }}">
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">推广日统计 </h3>
        <div class="box-tools">
           
        </div>
    </div>
    <div class="box-body table-responsive no-padding">
        <table class="table table-hover" id="example">
            <thead>
                <tr>
                    <th>日期</th>
                    <th>点击pv</th>
                    <th>点击下载pv</th>
                </tr>
                @foreach($data as $v)
                <tr>    
                    <td>{{$v['date']}}</td><td>{{$v['view_num']}}</td> <td>{{$v['down_num']}}</td>
                </tr>
                @endforeach

            </thead>

        </table>
    </div>
</div>
