<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\StrengthsTestAnswer;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;

class StrengthsTestAnswerController extends AdminController
{
    protected $translation = 'strengths-test-answer';

    protected function grid()
    {
        return Grid::make(new StrengthsTestAnswer(), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->column('test_type');
            $grid->column('result_code');
            $grid->column('result_name');
            $grid->column('summary')->limit(30);
            $grid->column('traits_summary')->limit(30);
            $grid->column('sort')->sortable();
            $grid->column('status')->using([0 => '禁用', 1 => '启用']);
            $grid->column('created_at')->sortable();
            $grid->column('updated_at')->sortable();

            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('test_type');
                $filter->equal('result_code');
                $filter->like('result_name');
                $filter->equal('status')->select([0 => '禁用', 1 => '启用']);
            });
        });
    }

    protected function detail($id)
    {
        return Show::make($id, new StrengthsTestAnswer(), function (Show $show) {
            $show->field('id');
            $show->field('test_type');
            $show->field('result_code');
            $show->field('result_name');
            $show->field('summary');
            $show->field('traits_summary');
            $show->field('traits');
            $show->field('strengths');
            $show->field('weaknesses');
            $show->field('careers');
            $show->field('typical_figures');
            $show->field('sort');
            $show->field('status')->using([0 => '禁用', 1 => '启用']);
            $show->field('created_at');
            $show->field('updated_at');
        });
    }

    protected function form()
    {
        return Form::make(new StrengthsTestAnswer(), function (Form $form) {
            $form->display('id');
            $form->text('test_type')->required()->rules('required|max:20');
            $form->text('result_code')->required()->rules('required|max:50');
            $form->text('result_name')->required()->rules('required|max:100');
            $form->text('summary')->rules('max:200');
            $form->text('traits_summary')->rules('max:200');
            $form->textarea('traits');
            $form->textarea('strengths');
            $form->textarea('weaknesses');
            $form->textarea('careers');
            $form->text('typical_figures')->rules('max:200');
            $form->number('sort')->default(0);
            $form->switch('status')->default(1);
            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
