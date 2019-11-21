<?php

namespace App\Admin\Controllers;

use App\Models\BeautifulImg;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Show;
use App\Models\AdminModel;

class BeautifulImgController extends Controller
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
            ->header('美图管理')
            ->description('美图管理')
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
            ->header('美图管理')
            ->description('美图管理')
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
            ->header('美图管理')
            ->description('美图管理')
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
            ->header('美图管理')
            ->description('美图管理')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new BeautifulImg);
        $grid->id('ID')->sortable();
        $grid->cover('封面图')->image('',70, 70);
        $grid->desc('描述');
        $grid->title('标题');
        $grid->praise('点赞数');
        $grid->upload('下载数');
        $grid->browse('浏览数');
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
        $show = new Show(BeautifulImg::findOrFail($id));



        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $array = (new AdminModel('img_sort'))->getAll();
        $sort = [];
        foreach ($array as $v) {
            $sort[$v['id']] = $v['name'];
        }
        $form = new Form(new BeautifulImg);
        $form->display('id', 'ID');
        $form->text('title', '标题');
        $form->select('sort', '分类')->options($sort);        
        // $form->text('desc', '描述');
        // $form->image('cover','封面图')->uniqueName()->move('beautiful_img')->options(['overwriteInitial' => true]);
        $form->multipleImage('cover','封面图')->help('请上传3张图')->removable()->uniqueName()->move('beautiful_img')->options(['overwriteInitial' => true]);
        $form->multipleImage('image','内容图片')->help('请上传多张内容图')->removable()->uniqueName()->move('beautiful_img')->options(['overwriteInitial' => true]);
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
