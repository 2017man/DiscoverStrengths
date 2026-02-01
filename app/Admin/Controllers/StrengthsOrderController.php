<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\StrengthsOrder;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;

class StrengthsOrderController extends AdminController
{
    protected $translation = 'strengths-order';

    protected function grid()
    {
        return Grid::make(new StrengthsOrder(), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->column('out_trade_no');
            $grid->column('test_result_id')->sortable();
            $grid->column('test_type');
            $grid->column('openid')->limit(20);
            $grid->column('amount')->sortable();
            $grid->column('status')->using([
                'pending' => '待支付',
                'paid' => '已支付',
                'failed' => '失败',
                'closed' => '已关闭',
            ]);
            $grid->column('pay_channel');
            $grid->column('paid_at')->sortable();
            $grid->column('created_at')->sortable();
            $grid->column('updated_at')->sortable();

            $grid->disableCreateButton();
            $grid->disableDeleteButton();

            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('out_trade_no');
                $filter->equal('test_result_id');
                $filter->equal('test_type');
                $filter->equal('status')->select([
                    'pending' => '待支付',
                    'paid' => '已支付',
                    'failed' => '失败',
                    'closed' => '已关闭',
                ]);
                $filter->like('openid');
            });
        });
    }

    protected function detail($id)
    {
        return Show::make($id, new StrengthsOrder(), function (Show $show) {
            $show->field('id');
            $show->field('out_trade_no');
            $show->field('test_result_id');
            $show->field('test_type');
            $show->field('openid');
            $show->field('amount');
            $show->field('status')->using([
                'pending' => '待支付',
                'paid' => '已支付',
                'failed' => '失败',
                'closed' => '已关闭',
            ]);
            $show->field('pay_channel');
            $show->field('paid_at');
            $show->field('created_at');
            $show->field('updated_at');
        });
    }

    protected function form()
    {
        return Form::make(new StrengthsOrder(), function (Form $form) {
            $form->display('id');
            $form->display('out_trade_no');
            $form->display('test_result_id');
            $form->display('test_type');
            $form->display('openid');
            $form->display('amount');
            $form->display('status');
            $form->display('pay_channel');
            $form->display('paid_at');
            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
