<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\HelperDictionary;
use App\Admin\Repositories\HelperDictionaryInfo;
use App\Models\HelperDictionary as ModHelperDictionary;
use App\Models\HelperDictionaryInfo as ModHelperDictionaryInfo;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Support\Helper;

class HelperDictionaryController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new HelperDictionary, function (Grid $grid) {
            // 禁用编辑按钮
            $grid->disableEditButton();
            // 显示快捷编辑按钮
            $grid->showQuickEditButton();
            $grid->enableDialogCreate();

            $grid->column('id')->sortable();
            $grid->column('name');
            $grid->column('code');
            $grid->column('created_at');
            $grid->column('updated_at')->sortable();

            $grid->quickSearch(['name', 'code'])->placeholder('搜索...');

        });
    }

    public function show($id, Content $content)
    {

        return $content
            ->translation($this->translation())
            ->title($this->title())
            ->description($this->description()['show'] ?? trans('admin.show'))
            ->body($this->detail($id));
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     *
     * @return Show
     */
    protected function detail($id)
    {
        return Show::make($id, new HelperDictionary, function (Show $show) {
            //$show->field('id');
            $show->field('name')->width(4);
            $show->field('code')->width(4);
            // 不显示
            $show->field('created_at')->width(4);
            //$show->field('updated_at');
            $show->relation('info', '字典信息项', function ($model) {
                $grid = new Grid(new HelperDictionaryInfo);

                // 关联字段数据
                $grid->model()->where('dictionary_id', $model->id);
                // 设置路由
                $grid->setResource('HelperDictionaryInfo');

                //$grid->column('id');
                $grid->column('value', '信息项名称');
                $grid->column('code', '信息项编码');
                $grid->filter(function ($filter) {
                    $filter->like('value')->width('300px');
                });
                return $grid;

            });


        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        // 这里需要显式地指定关联关系
        //$builder = ModHelperDictionary::with('info');

        // 如果你使用的是数据仓库，则可以这样指定关联关系
        $builder = new HelperDictionary(['info']);

        return Form::make($builder, function (Form $form) {
            //$form->display('id');
            $form->text('name')->width(4);
            $form->text('code')->width(4);

            // 一对多表单数据添加
            $form->hasMany('info', '字典信息项', function (Form\NestedForm $form) {
                $form->text('value', '信息项名称');
                $form->text('code', '信息项编码');
            })->useTable();

            //$form->display('created_at');
            //$form->display('updated_at');
        });
    }
}
