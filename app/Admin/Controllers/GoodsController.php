<?php

namespace App\Admin\Controllers;

use App\Models\Goods;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Show;
use App\Models\AdminModel;

class GoodsController extends Controller
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
            ->header('商品管理')
            ->description('商品管理')
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
            ->header('商品管理')
            ->description('商品管理')
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
            ->header('商品管理')
            ->description('商品管理')
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
            ->header('商品管理')
            ->description('商品管理')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Goods);
        $grid->id('ID')->sortable();
        $grid->name('名称');
        $grid->type('分类')->display(function($status) {
            $arr = (new AdminModel('goods_type'))->getAll('',['type','id']);
            foreach ($arr as $key => $value) {
                if($status==$value['id']) return $value['type']; 
            }
            
        });
        $grid->cover('商品大图')->image('',70, 70);
        $grid->image('详情图')->image('',70, 70);
        $grid->stock('库存');
        $grid->price('积分价格');
        $grid->status('状态')->display(function($status) {
            if($status==1) return "上架中";
            if($status==0) return "已下架"; 
        });
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
        $show = new Show(Goods::findOrFail($id));



        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Goods);
        $form->display('id', 'ID');
        $form->text('name', '名称');
        $arr = (new AdminModel('goods_type'))->getAll('',['type','id']);
        $array = [];
        foreach ($arr as $key => $value) {

            $array[$value['id']] = $value['type'];
        }
        $form->select('type', '分类')->options($array);
        $form->text('stock', '库存');
        $form->text('price', '积分价格');
        $form->textarea('desc', '详情');
        $form->switch('status', '是否上架')->value(0);
        $form->image('cover','封面图')->uniqueName()->move('goods_img')->options(['overwriteInitial' => true]);
        $form->multipleImage('image','内容图片')->help('请上传多张内容图')->removable()->uniqueName()->move('goods_img')->options(['overwriteInitial' => true]);
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
