<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\StrengthsTestDimensionSide;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;

class StrengthsTestDimensionSideController extends AdminController
{
    protected $translation = 'strengths-test-dimension-side';

    protected function grid()
    {
        return Grid::make(new StrengthsTestDimensionSide(), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->column('test_type');
            $grid->column('dimension_code');
            $grid->column('side_code');
            $grid->column('side_name');
            $grid->column('name_en');
            $grid->column('overview')->limit(30);
            $grid->column('sort')->sortable();
            $grid->column('created_at')->sortable();
            $grid->column('updated_at')->sortable();

            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('test_type');
                $filter->equal('dimension_code');
                $filter->equal('side_code');
            });
        });
    }

    protected function detail($id)
    {
        return Show::make($id, new StrengthsTestDimensionSide(), function (Show $show) {
            $show->field('id');
            $show->field('test_type');
            $show->field('dimension_code');
            $show->field('side_code');
            $show->field('side_name');
            $show->field('name_en');
            $show->field('overview');
            $show->field('features');
            $show->field('keywords');
            $show->field('style');
            $show->field('expression');
            $show->field('mantra');
            $show->field('sort');
            $show->field('created_at');
            $show->field('updated_at');
        });
    }

    protected function form()
    {
        return Form::make(new StrengthsTestDimensionSide(), function (Form $form) {
            $form->display('id');
            $form->text('test_type')->required()->rules('required|max:20');
            $form->text('dimension_code')->required()->rules('required|max:20');
            $form->text('side_code')->required()->rules('required|max:2');
            $form->text('side_name')->required()->rules('required|max:50');
            $form->text('name_en')->rules('max:50');
            $form->textarea('overview');
            $form->textarea('features');
            $form->textarea('keywords');
            $form->textarea('style');
            $form->textarea('expression');
            $form->text('mantra')->rules('max:200');
            $form->number('sort')->default(0);
            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
