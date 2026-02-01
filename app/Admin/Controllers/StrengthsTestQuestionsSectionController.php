<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\StrengthsTestQuestionsSection;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;

class StrengthsTestQuestionsSectionController extends AdminController
{
    protected $translation = 'strengths-test-questions-section';

    protected function grid()
    {
        return Grid::make(new StrengthsTestQuestionsSection(), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->column('test_type');
            $grid->column('section_code');
            $grid->column('section_title')->limit(40);
            $grid->column('has_questions')->using([0 => '无', 1 => '有']);
            $grid->column('sort')->sortable();
            $grid->column('created_at')->sortable();
            $grid->column('updated_at')->sortable();

            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('test_type');
                $filter->equal('section_code');
            });
        });
    }

    protected function detail($id)
    {
        return Show::make($id, new StrengthsTestQuestionsSection(), function (Show $show) {
            $show->field('id');
            $show->field('test_type');
            $show->field('section_code');
            $show->field('section_title');
            $show->field('has_questions')->using([0 => '无', 1 => '有']);
            $show->field('sort');
            $show->field('created_at');
            $show->field('updated_at');
        });
    }

    protected function form()
    {
        return Form::make(new StrengthsTestQuestionsSection(), function (Form $form) {
            $form->display('id');
            $form->text('test_type')->required()->rules('required|max:20');
            $form->text('section_code')->required()->rules('required|max:20');
            $form->textarea('section_title');
            $form->radio('has_questions', '是否有题目')->options([1 => '有', 0 => '无'])->default(1);
            $form->number('sort')->default(0);
            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
