<?php

namespace App\Admin\Controllers;

use App\Models\Book;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Show;

class Book_Controller extends Controller
{
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header('书籍管理')
            ->description('书籍管理')
            ->body($this->grid());
    }

    /**
     * Show interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header('书籍管理')
            ->description('书籍管理')
            ->body($this->detail($id));
    }

    /**
     * Edit interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header('书籍管理')
            ->description('书籍管理')
            ->body($this->form()->edit($id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header('书籍管理')
            ->description('书籍管理')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Book);
        $grid->model()->latest();
        $grid->id('ID')->sortable();
        // $grid->title('书名')->sortable();
        // $grid->title('书名')->link("http://read.com/admin/book/".$this->data('id'));
        $grid->title('书名');

        $grid->author('作者')->sortable();
        $grid->sortid('分类')->display(function($sortid) {
            if($sortid==0) return "未分类";
            if($sortid==1) return "言情";
            if($sortid==2) return "校园"; 
            if($sortid==3) return "玄幻";
            if($sortid==4) return "恐怖"; 
            if($sortid==5) return "悬疑";
            if($sortid==6) return "社会"; 
            if($sortid==7) return "战争";
            if($sortid==8) return "自传"; 

        });

        $grid->desc('描述');
        $grid->keywords('关键词');
        $grid->word_count('总字数')->sortable();
        $grid->finish('是否完结')->display(function($status) {
            if($status==0) return "未完结";
            if($status==1) return "已完结"; 
        });
        $grid->image('封面图')->image('',70, 70);
        $grid->created_at('新增时间')->sortable();

       
        $grid->disableExport();//禁用导出数据按钮
        $grid->filter(function ($filter) {

            //$filter->disableIdFilter();//禁用查询过滤器
            $filter->like('title', '书名');
            $filter->equal('finish', '完结状态')->select(["未完结","已完结"]);
        });
        

        $this->script = <<<EOT
                  
$('.grid-row-sure').unbind('click').click(function() {
    var that = $(this);
    var id = that.data('id');
    window.location.href="http://read.com/admin/chapter?book_id="+id;
});
EOT;
        Admin::script($this->script);


        $grid->actions(function ($actions) {
            $actions->append("<div class='mb-5'><a class='btn btn-xs action-btn btn-success grid-row-sure' data-id='{$actions->getKey()}'> 查看章节</a></div>");
        });


        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Book::findOrFail($id));



        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Book);
        $form->display('id', 'ID');
        $form->text('title', '书名');
        $form->text('author', '作者');
        $form->text('desc', '描述');
        $select_label = ['未分类','言情','校园','玄幻','恐怖','悬疑','社会','战争','自传'];
        $form->select('sortid', '分类')->options($select_label);
        $form->text('keywords', '关键词');
        $form->text('word_count', '总字数');
        $form->switch('finish', '是否完结')->value(0);
        $form->image('image','上传图片')->uniqueName()->move('book_pic')->options(['overwriteInitial' => true]);
      
        //$form->image('img','上传图片')->uniqueName()->move('homeRotaryMap')->options(['overwriteInitial' => true]);
        // $select_label = ['纸本'=>'纸本','油画'=>'油画','中国画'=>'中国画','版画'=>'版画','水彩'=>'水彩','红'=>'红','橙'=>'橙','黄'=>'黄','绿'=>'绿','蓝'>'蓝','靛'=>'靛','紫'=>'紫','素色'=>'素色','深色'=>'深色','浅色'=>'浅色','浓郁'=>'浓郁','素雅'=>'素雅'];
        // $form->select('label', '选择标签')->options($select_label);
        // $form->switch('status', '使用状态')->value(1);
        // $form->hidden('creater')->value(Admin::user()->id);
        // $form->hidden('type')->value(2);
        // $form->tools(function (Form\Tools $tools){
        //     $tools->disableList();
        // });

        return $form;
    }
}
