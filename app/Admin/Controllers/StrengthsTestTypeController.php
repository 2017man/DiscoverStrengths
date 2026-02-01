<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\StrengthsTestType;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;

class StrengthsTestTypeController extends AdminController
{
    protected $translation = 'strengths-test-type';

    protected function grid()
    {
        return Grid::make(new StrengthsTestType(), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->column('code')->editable();
            $grid->column('name')->editable();
            $grid->column('description')->limit(30);
            $grid->column('total_questions')->sortable();
            $grid->column('price')->sortable();
            $grid->column('sort')->sortable()->editable();
            $grid->column('status')->switch();
            $grid->column('created_at')->sortable();
            $grid->column('updated_at')->sortable();

            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('code');
                $filter->like('name');
                $filter->equal('status')->select([1 => '启用', 0 => '禁用']);
            });
        });
    }

    protected function detail($id)
    {
        return Show::make($id, new StrengthsTestType(), function (Show $show) {
            $show->field('id');
            $show->field('code');
            $show->field('name');
            $show->field('description');
            $show->field('total_questions');
            $show->field('price');
            $show->field('sort');
            $show->field('status')->using([0 => '禁用', 1 => '启用']);
            $show->field('created_at');
            $show->field('updated_at');
        });
    }

    protected function form()
    {
        return Form::make(new StrengthsTestType(), function (Form $form) {
            $form->display('id');
            $form->text('code')->required()->rules('required|max:20');
            $form->text('name')->required()->rules('required|max:50');
            $form->textarea('description');
            $form->number('total_questions')->default(0)->min(0);
            $form->decimal('price', '单价(元)')->default(8.88)->required();
            $form->number('sort')->default(0);
            $form->switch('status')->default(1);
            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
