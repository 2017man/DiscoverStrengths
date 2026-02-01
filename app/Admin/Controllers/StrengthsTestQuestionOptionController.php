<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\StrengthsTestQuestionOption;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;

class StrengthsTestQuestionOptionController extends AdminController
{
    protected $translation = 'strengths-test-question-option';

    protected function grid()
    {
        return Grid::make(new StrengthsTestQuestionOption(), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->column('test_type');
            $grid->column('section_code');
            $grid->column('question_number')->sortable();
            $grid->column('option_key');
            $grid->column('option_text')->limit(40);
            $grid->column('dimension_side');
            $grid->column('created_at')->sortable();
            $grid->column('updated_at')->sortable();

            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('test_type');
                $filter->equal('section_code');
                $filter->equal('question_number');
                $filter->equal('dimension_side');
            });
        });
    }

    protected function detail($id)
    {
        return Show::make($id, new StrengthsTestQuestionOption(), function (Show $show) {
            $show->field('id');
            $show->field('test_type');
            $show->field('section_code');
            $show->field('question_number');
            $show->field('option_key');
            $show->field('option_text');
            $show->field('dimension_side');
            $show->field('created_at');
            $show->field('updated_at');
        });
    }

    protected function form()
    {
        return Form::make(new StrengthsTestQuestionOption(), function (Form $form) {
            $form->display('id');
            $form->text('test_type')->required()->rules('required|max:20');
            $form->text('section_code')->required()->rules('required|max:20');
            $form->number('question_number')->required()->min(1);
            $form->text('option_key')->required()->rules('required|max:10');
            $form->textarea('option_text')->required();
            $form->select('dimension_side')->options([
                'E' => 'E', 'I' => 'I', 'S' => 'S', 'N' => 'N',
                'T' => 'T', 'F' => 'F', 'J' => 'J', 'P' => 'P',
            ])->required();
            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
