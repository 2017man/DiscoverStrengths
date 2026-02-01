<?php

namespace App\Admin\Controllers;

use App\Models\HelperDictionary;
use App\Models\MemberRelation;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;

class MemberRelationController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $relationOpts = HelperDictionary::getInfoKeyVal('QSGX_CODE');
        return Grid::make(MemberRelation::with(['user', 'company'])->orderBy('updated_at', 'desc'), function (Grid $grid) use ($relationOpts) {
            // 禁用编辑按钮
            $grid->disableEditButton();
            $grid->disableCreateButton();
            $grid->disableDeleteButton();
            // 显示快捷编辑按钮
            //$grid->showQuickEditButton();
            //$grid->enableDialogCreate();

            //$grid->column('id')->sortable();

            $grid->column('user.name', '员工姓名');
            $grid->column('user.department', '员工部门');

            $grid->column('user.phone', '员工电话')->copyable();
            $grid->column('company.name', '集团单位');

            $grid->column('supervisor_name')->display(function ($val) {
                return encrypFirstStr($val);
            });
            $grid->column('supervisor_position')->display(function ($val) {
                return encrypFirstStr($val);
            });
            $grid->column('supervisor_phone')->display(function ($val) {
                return encrypTel($val);
            });
            //$grid->column('created_at');
            $grid->column('updated_at')->sortable();

            $grid->quickSearch(['supervisor_name', 'supervisor_position', 'supervisor_phone'])->placeholder('输入姓名、职位、电话');
            // 导出功能
            $grid->export()
                ->titles([
                    'user_id'             => '用户id',
                    'user.name'           => '员工姓名',
                    'user.department'     => '员工部门',
                    'user.phone'          => '员工电话',
                    'user.job_number'     => '员工工号',
                    //'relation'            => '亲属关系',
                    'company.name'        => '集团单位',
                    'supervisor_name'     => '监督员姓名',
                    'supervisor_position' => '监督员职位',
                    'supervisor_phone'    => '监督员电话',
                    'created_at'          => '创建时间',
                    'updated_at'          => '更新时间',
                ])
                ->rows(function ($rows) use ($relationOpts) {
                    foreach ($rows as $index => &$row) {
                        //$row['relation'] = $relationOpts[$row->relation] ?? '';
                    }
                    return $rows;
                })
                ->xlsx();
        });
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     *
     * @return Show
     */
    protected function detail($id)
    {
        $relationOpts = HelperDictionary::getInfoKeyVal('QSGX_CODE');
        return Show::make($id, MemberRelation::with(['user', 'company']), function (Show $show) use ($relationOpts) {
            $show->disableEditButton();

            $show->field('id');
            $show->field('user_id');
            $show->field('user.name', '员工姓名');
            $show->field('user.department', '员工部门');
            $show->field('user.phone', '员工电话');
            $show->field('user.job_number', '员工工号');

            $show->field('supervisor_name');
            $show->field('supervisor_position');
            $show->field('supervisor_phone');
            $show->field('created_at');
            $show->field('updated_at');
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Form::make(new MemberRelation(), function (Form $form) {
            $form->display('id');
            $form->text('user_id');
            $form->select('relation')->options(env('APP_URL') . '/api/v1/com/options/QSGX_CODE');
            $form->text('supervisor_name');
            $form->text('supervisor_position');

            $form->text('supervisor_phone');

            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
