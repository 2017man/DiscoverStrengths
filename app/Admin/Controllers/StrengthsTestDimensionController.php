<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\StrengthsTestDimension;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;

class StrengthsTestDimensionController extends AdminController
{
    protected $translation = 'strengths-test-dimension';

    protected function grid()
    {
        return Grid::make(new StrengthsTestDimension(), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->column('test_type');
            $grid->column('dimension_code');
            $grid->column('dimension_aspect');
            $grid->column('dimension_trait_pair');
            $grid->column('dimension_scope')->limit(30);
            $grid->column('sort')->sortable();
            $grid->column('created_at')->sortable();
            $grid->column('updated_at')->sortable();

            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('test_type');
                $filter->equal('dimension_code');
            });
        });
    }

    protected function detail($id)
    {
        return Show::make($id, new StrengthsTestDimension(), function (Show $show) {
            $show->field('id');
            $show->field('test_type');
            $show->field('dimension_code');
            $show->field('dimension_aspect');
            $show->field('dimension_trait_pair');
            $show->field('dimension_scope');
            $show->field('dimension_summary');
            $show->field('dimension_narrative');
            $show->field('dimension_context');
            $show->field('sort');
            $show->field('created_at');
            $show->field('updated_at');
        });
    }

    protected function form()
    {
        return Form::make(new StrengthsTestDimension(), function (Form $form) {
            $form->display('id');
            $form->text('test_type')->required()->rules('required|max:20');
            $form->text('dimension_code')->required()->rules('required|max:20');
            $form->text('dimension_aspect')->required()->rules('required|max:50');
            $form->text('dimension_trait_pair')->required()->rules('required|max:50');
            $form->text('dimension_scope')->rules('max:100');
            $form->textarea('dimension_summary');
            $form->textarea('dimension_narrative');
            $form->textarea('dimension_context');
            $form->number('sort')->default(0);
            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
