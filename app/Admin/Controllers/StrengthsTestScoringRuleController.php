<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\StrengthsTestScoringRule;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;

class StrengthsTestScoringRuleController extends AdminController
{
    protected $translation = 'strengths-test-scoring-rule';

    protected function grid()
    {
        return Grid::make(new StrengthsTestScoringRule(), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->column('test_type');
            $grid->column('rule_name');
            $grid->column('rule_type');
            $grid->column('calculate_method');
            $grid->column('top_count');
            $grid->column('description')->limit(30);
            $grid->column('created_at')->sortable();
            $grid->column('updated_at')->sortable();

            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('test_type');
                $filter->like('rule_name');
            });
        });
    }

    protected function detail($id)
    {
        return Show::make($id, new StrengthsTestScoringRule(), function (Show $show) {
            $show->field('id');
            $show->field('test_type');
            $show->field('rule_name');
            $show->field('rule_type');
            $show->field('calculate_method');
            $show->field('top_count');
            $show->field('description');
            $show->field('created_at');
            $show->field('updated_at');
        });
    }

    protected function form()
    {
        return Form::make(new StrengthsTestScoringRule(), function (Form $form) {
            $form->display('id');
            $form->text('test_type')->required()->rules('required|max:20');
            $form->text('rule_name')->required()->rules('required|max:50');
            $form->text('rule_type')->required()->rules('required|max:20');
            $form->text('calculate_method')->rules('max:50');
            $form->number('top_count');
            $form->textarea('description');
            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
