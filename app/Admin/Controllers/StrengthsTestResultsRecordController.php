<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\StrengthsTestResultsRecord;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;

class StrengthsTestResultsRecordController extends AdminController
{
    protected $translation = 'strengths-test-results-record';

    protected function grid()
    {
        return Grid::make(new StrengthsTestResultsRecord(), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->column('test_type');
            $grid->column('result_code');
            $grid->column('openid')->limit(20);
            $grid->column('session_id')->limit(20);
            $grid->column('is_paid')->using([0 => '未付费', 1 => '已付费']);
            $grid->column('paid_at')->sortable();
            $grid->column('created_at')->sortable();
            $grid->column('updated_at')->sortable();

            $grid->disableCreateButton();
            $grid->disableDeleteButton();
            $grid->actions(function (Grid\Displayers\Actions $actions) {
                $actions->disableEdit();
                $actions->disableDelete();
            });

            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('test_type');
                $filter->equal('result_code');
                $filter->equal('is_paid')->select([0 => '未付费', 1 => '已付费']);
                $filter->like('openid');
            });
        });
    }

    protected function detail($id)
    {
        return Show::make($id, new StrengthsTestResultsRecord(), function (Show $show) {
            $show->field('id');
            $show->field('test_type');
            $show->field('result_code');
            $show->field('openid');
            $show->field('session_id');
            $show->field('answers_snapshot');
            $show->field('is_paid')->using([0 => '未付费', 1 => '已付费']);
            $show->field('paid_at');
            $show->field('created_at');
            $show->field('updated_at');
        });
    }

    protected function form()
    {
        return Form::make(new StrengthsTestResultsRecord(), function (Form $form) {
            $form->display('id');
            $form->display('test_type');
            $form->display('result_code');
            $form->display('openid');
            $form->display('session_id');
            $form->display('is_paid');
            $form->display('paid_at');
            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
