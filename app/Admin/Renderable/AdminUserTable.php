<?php


namespace App\Admin\Renderable;

use App\Models\CompanyConfig;
use App\Models\HelperDictionary;
use App\Models\Member;
use Dcat\Admin\Admin;
use Dcat\Admin\Grid;
use Dcat\Admin\Grid\LazyRenderable;
use Dcat\Admin\Http\Repositories\Administrator;

class AdminUserTable extends LazyRenderable
{
    public function grid(): Grid
    {
        // 获取外部传递的参数
        $id = $this->id;
        // 必须要指定吗，自己就不能寻找吗
        Admin::translation('user-new');

        // TODO 筛选客户经理
        $modMember = Member::with(['roles' => function ($query) {
            return $query->whereIn('id', [2]);
        }])
            ->where('id', '>', 1);
        return Grid::make($modMember, function (Grid $grid) {
            //$grid->column('username');
            $grid->column('name');
            $grid->column('department');
            $grid->column('phone');
            $grid->column('job_number');


            $grid->quickSearch(['id', 'name', 'phone', 'job_number']);

            $grid->paginate(10);
            $grid->disableActions();

        });
    }
}
