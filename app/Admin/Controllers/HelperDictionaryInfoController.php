<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\HelperDictionaryInfo;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;

class HelperDictionaryInfoController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new HelperDictionaryInfo(['dictionary']), function (Grid $grid) {
            $grid->column('id')->sortable();
            //$grid->column('dictionary_id');
            $grid->column('value');
            $grid->column('code');
            $grid->column('created_at');
            $grid->column('updated_at')->sortable();

            $grid->quickSearch(['code'])->placeholder('搜索...');

        });
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
        return Show::make($id, new HelperDictionaryInfo(), function (Show $show) {
            $show->field('id');
            //$show->field('dictionary_id');
            $show->field('value');
            $show->field('code');
            $show->field('created_at');
            $show->field('updated_at');
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Form::make(new HelperDictionaryInfo(), function (Form $form) {
            $form->display('id');
            $form->text('value');
            $form->text('code');
            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
