<?php

namespace App\Admin\Controllers;

use App\Models\BookSort;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Show;

class BookSortController extends Controller
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
            ->header('书籍分类')
            ->description('书籍分类')
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
            ->header('书籍分类')
            ->description('书籍分类')
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
            ->header('书籍分类')
            ->description('书籍分类')
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
            ->header('书籍分类')
            ->description('书籍分类')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new BookSort);
        $grid->id('ID')->sortable();
        $grid->sort('类型');
        $grid->channel('渠道')->display(function($channel) {
            if($channel==1) return "男频";
            if($channel==2) return "女频";
            if($channel==3) return "漫画"; 
        });;
        $grid->disableExport();//禁用导出数据按钮
        $grid->filter(function ($filter) {
            $filter->disableIdFilter();//禁用查询过滤器
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
        $show = new Show(BookSort::findOrFail($id));



        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new BookSort);
        $form->display('id', 'ID');
        $form->text('sort', '分类名称');
        $form->select('channel', '频道')->options([1=>"男频",2=>"女频",3=>"漫画"]);
        $form->footer(function ($footer) {
            // 去掉`查看`checkbox
            $footer->disableViewCheck();
            // 去掉`继续编辑`checkbox
            $footer->disableEditingCheck();
            // 去掉`继续创建`checkbox
            $footer->disableCreatingCheck();

        });
        return $form;
    }
}
