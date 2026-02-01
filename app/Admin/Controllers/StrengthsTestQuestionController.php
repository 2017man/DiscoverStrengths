<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\StrengthsTestQuestion;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;

class StrengthsTestQuestionController extends AdminController
{
    protected $translation = 'strengths-test-question';

    protected function grid()
    {
        return Grid::make(new StrengthsTestQuestion(), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->column('test_type');
            $grid->column('section_code');
            $grid->column('question_number')->sortable();
            $grid->column('question_text')->limit(50);
            $grid->column('sort')->sortable();
            $grid->column('created_at')->sortable();
            $grid->column('updated_at')->sortable();

            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('test_type');
                $filter->equal('section_code');
                $filter->equal('question_number');
            });
        });
    }

    protected function detail($id)
    {
        return Show::make($id, new StrengthsTestQuestion(), function (Show $show) {
            $show->field('id');
            $show->field('test_type');
            $show->field('section_code');
            $show->field('question_number');
            $show->field('question_text');
            $show->field('sort');
            $show->field('created_at');
            $show->field('updated_at');
        });
    }

    protected function form()
    {
        return Form::make(new StrengthsTestQuestion(), function (Form $form) {
            $form->display('id');
            $form->text('test_type')->required()->rules('required|max:20');
            $form->text('section_code')->rules('max:20');
            $form->number('question_number')->required()->min(1);
            $form->textarea('question_text')->required();
            $form->number('sort')->default(0);
            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
