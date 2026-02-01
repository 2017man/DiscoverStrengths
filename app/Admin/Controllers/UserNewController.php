<?php

namespace App\Admin\Controllers;

use App\Admin\Renderable\CompanyConfigTable;
use App\Models\CompanyConfig;
use App\Models\HelperDictionary;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\UserController;
use Dcat\Admin\Http\Repositories\Administrator;
use Dcat\Admin\Models\Administrator as AdministratorModel;
use Dcat\Admin\Show;
use Dcat\Admin\Widgets\Tree;

/**
 * 用户管理扩展
 * Class UserExtendController
 * @package App\Admin\Controllers
 */
class UserNewController extends UserController
{
    public function title()
    {
        return trans('admin.user');
    }

    protected function grid()
    {
        return Grid::make(Administrator::with(['roles']), function (Grid $grid) {
            $grid->column('id', 'ID')->sortable();
            $grid->column('username');
            $grid->column('name');
            //$grid->column('area')->display(function ($val) {
            //    $companyConf = HelperDictionary::getInfoKeyVal('AREA_CODE');
            //    return $companyConf[$val] ?? '';
            //});
            $grid->column('department');
            $grid->column('phone');
            $grid->column('job_number');

            if (config('admin.permission.enable')) {
                $grid->column('roles')->pluck('name')->label('primary', 3);

                $permissionModel = config('admin.database.permissions_model');
                $roleModel       = config('admin.database.roles_model');
                $nodes           = (new $permissionModel())->allNodes();
                $grid->column('permissions')
                    ->if(function () {
                        return !$this->roles->isEmpty();
                    })
                    ->showTreeInDialog(function (Grid\Displayers\DialogTree $tree) use (&$nodes, $roleModel) {
                        $tree->nodes($nodes);

                        foreach (array_column($this->roles->toArray(), 'slug') as $slug) {
                            if ($roleModel::isAdministrator($slug)) {
                                $tree->checkAll();
                            }
                        }
                    })
                    ->else()
                    ->display('');
            }

            $grid->column('created_at');
            $grid->column('updated_at')->sortable();

            $grid->quickSearch(['id', 'name', 'username']);

            $grid->showQuickEditButton();
            $grid->enableDialogCreate();
            $grid->showColumnSelector();
            $grid->disableEditButton();

            $grid->actions(function (Grid\Displayers\Actions $actions) {
                if ($actions->getKey() == AdministratorModel::DEFAULT_ID) {
                    $actions->disableDelete();
                }
            });
        });
    }

    protected function detail($id)
    {
        return Show::make($id, Administrator::with(['roles']), function (Show $show) {
            $show->field('username');
            $show->field('name');
            $show->field('area')->as(function ($val) {
                $companyConf = HelperDictionary::getInfoKeyVal('AREA_CODE');
                return $companyConf[$val] ?? '';
            });
            $show->field('phone');
            $show->field('name');
            $show->field('job_number');
            $show->field('company_codes')->as(function ($codes) {
                return CompanyConfig::getCompanyName(json_decode($codes, true));
            })->label();

            $show->field('avatar', __('admin.avatar'))->image();

            if (config('admin.permission.enable')) {
                $show->field('roles')->as(function ($roles) {
                    if (!$roles) {
                        return;
                    }

                    return collect($roles)->pluck('name');
                })->label();

                $show->field('permissions')->unescape()->as(function () {
                    $roles = $this->roles->toArray();

                    $permissionModel = config('admin.database.permissions_model');
                    $roleModel       = config('admin.database.roles_model');
                    $permissionModel = new $permissionModel();
                    $nodes           = $permissionModel->allNodes();

                    $tree = Tree::make($nodes);

                    $isAdministrator = false;
                    foreach (array_column($roles, 'slug') as $slug) {
                        if ($roleModel::isAdministrator($slug)) {
                            $tree->checkAll();
                            $isAdministrator = true;
                        }
                    }

                    if (!$isAdministrator) {
                        $keyName = $permissionModel->getKeyName();
                        $tree->check(
                            $roleModel::getPermissionId(array_column($roles, $keyName))->flatten()
                        );
                    }

                    return $tree->render();
                });
            }

            $show->field('created_at');
            $show->field('updated_at');
        });
    }

    public function form()
    {
        return Form::make(Administrator::with(['roles']), function (Form $form) {
            $userTable = config('admin.database.users_table');

            $connection = config('admin.database.connection');

            $id = $form->getKey();
            $form->hidden('username', trans('admin.username'));
            $form->text('name')->maxLength('50')->required();
            $form->select('area')->options(env('APP_URL') . '/api/v1/com/options/AREA_CODE');
            $form->mobile('phone')->required();
            $form->text('job_number')->required()
                ->creationRules(['required', "unique:{$connection}.{$userTable}"])
                ->updateRules(['required', "unique:{$connection}.{$userTable},job_number,$id"]);;

            $form->multipleSelectTable('company_codes')
                ->title('请选择集团单位')
                ->from(CompanyConfigTable::make(['id' => $form->getKey()]))
                ->model(CompanyConfig::class, 'code', 'name')
                ->saveAsJson();
            $form->image('avatar', trans('admin.avatar'))->autoUpload();

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
            $form->username = $form->job_number;
        });
    }
}
