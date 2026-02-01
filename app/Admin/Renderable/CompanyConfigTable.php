<?php


namespace App\Admin\Renderable;

use App\Models\CompanyConfig;
use Dcat\Admin\Admin;
use Dcat\Admin\Grid;
use Dcat\Admin\Grid\LazyRenderable;

class CompanyConfigTable extends LazyRenderable
{
    public function grid(): Grid
    {
        // 获取外部传递的参数
        $id = $this->id;
        // 必须要指定吗，自己就不能寻找吗
        Admin::translation('company-config');

        return Grid::make(new CompanyConfig(), function (Grid $grid) {
            //$grid->column('id');
            $grid->column('code');
            $grid->column('name');
            $grid->column('level');

            $grid->quickSearch(['code', 'name']);

            $grid->paginate(10);
            $grid->disableActions();

        });
    }
}
