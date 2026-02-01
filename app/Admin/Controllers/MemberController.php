<?php

namespace App\Admin\Controllers;


use App\Models\GatherInformation;
use App\Models\Member;
use App\Models\MemberRelation;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\UserController;
use Dcat\Admin\Http\Repositories\Administrator;
use Dcat\Admin\Models\Administrator as AdministratorModel;
use Dcat\Admin\Show;
use Dcat\Admin\Widgets\Tree;

/**
 * 员工信息
 * Class MemberController
 * @package App\Admin\Controllers
 */
class MemberController extends UserController
{

    public function title()
    {
        return '员工/网格信息';
    }

    protected function grid()
    {
        return Grid::make(Member::query()
            ->where('id', '>', 1), function (Grid $grid) {
            $grid->column('id', 'ID')->sortable();
            $grid->column('username');
            $grid->column('name');
            $grid->column('phone');
            $grid->column('job_number');
            $grid->column('department');


            $grid->column('relations_count', '监督员数')->display(function ($val) {
                return MemberRelation::query()->where('user_id', $this->id)->count();
            })->sortable()
                ->badge('primary');
            $grid->column('informations_count', '信息数')->display(function ($val) {
                return GatherInformation::query()->where('user_id', $this->id)->count();
            })
                ->badge('info');

            //$grid->column('updated_at')->sortable();

            $grid->quickSearch(['name', 'username', 'phone', 'job_number', 'department'])->placeholder('输入姓名、用户名、手机、工号、部门');

            $grid->showQuickEditButton();
            $grid->enableDialogCreate();
            $grid->showColumnSelector();
            $grid->disableEditButton();

            $grid->actions(function (Grid\Displayers\Actions $actions) {
                if ($actions->getKey() == AdministratorModel::DEFAULT_ID) {
                    $actions->disableDelete();
                }
            });
            // 导出功能
            $grid->export()
                ->xlsx();
        });
    }

    protected function detail($id)
    {
        return Show::make($id, Member::with(['relations', 'informations']), function (Show $show) {
            $show->field('username');
            $show->field('name');
            $show->field('phone');
            $show->field('name');
            $show->field('job_number');
            $show->field('department');

            $show->field('created_at');
            $show->field('updated_at');
        });
    }

    public function form()
    {
        return Form::make(Member::with(['roles', 'relations', 'informations']), function (Form $form) {
            $userTable = config('admin.database.users_table');

            $connection = config('admin.database.connection');

            $id = $form->getKey();
            $form->hidden('username', trans('admin.username'));
            $form->text('name')->maxLength('50')->required();
            $form->mobile('phone')->required();
            $form->select('department')->options(function ($department) {
                return Member::getCompany();
            })->required();
            $form->text('job_number')
                ->creationRules(['required', "unique:{$connection}.{$userTable}"])
                ->updateRules(['required', "unique:{$connection}.{$userTable},job_number,$id"]);
            $form->select('area')->options(function ($val) {
                return Member::query()->where('id','>',1)->distinct()->pluck('area','area');
            })->required();

            if ($id) {
                $form->password('password', trans('admin.password'))
                    ->minLength(5)
                    ->maxLength(20)
                    ->customFormat(function () {
                        return '';
                    });
            } else {
                $form->password('password', trans('admin.password'))
                    ->default('123456')// 设置值默认密码
                    ->required()
                    ->minLength(5)
                    ->maxLength(20);
            }

            $form->password('password_confirmation', trans('admin.password_confirmation'))->same('password');

            $form->ignore(['password_confirmation']);

            if (config('admin.permission.enable')) {
                $form->multipleSelect('roles', trans('admin.roles'))
                    ->options(function () {
                        $roleModel = config('admin.database.roles_model');

                        return $roleModel::all()->pluck('name', 'id');
                    })
                    ->customFormat(function ($v) {
                        return array_column($v, 'id');
                    });
            }

            $form->display('created_at', trans('admin.created_at'));
            $form->display('updated_at', trans('admin.updated_at'));

            if ($id == AdministratorModel::DEFAULT_ID) {
                $form->disableDeleteButton();
            }
        })->saving(function (Form $form) {

            if ($form->password && $form->model()->get('password') != $form->password) {
                $form->password = bcrypt($form->password);
            }

            if (!$form->password) {
                $form->deleteInput('password');
            }
            // TODO 设置用户名-username
            $form->username = $form->phone;
        });
    }
}
