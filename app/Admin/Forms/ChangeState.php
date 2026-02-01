<?php

namespace App\Admin\Forms;

use App\Constants\Common;
use App\Models\GatherInformation;
use App\Models\GatherInformationState;
use App\Service\StateFlow\AddStateFlow;
use App\Service\StateFlow\DiscardStateFlow;
use App\Service\StateFlow\PassStateFlow;
use App\Service\StateFlow\ReadStateFlow;
use Dcat\Admin\Admin;
use Dcat\Admin\Contracts\LazyRenderable;
use Dcat\Admin\Widgets\Form;
use Dcat\Admin\Traits\LazyWidget;
use Dcat\Admin\Models\Administrator;
use App\Admin\Renderable\AdminUserTable;

class ChangeState extends Form implements LazyRenderable
{
    use LazyWidget;

    /**
     * Handle the form request.
     *
     * @param array $input
     *
     * @return mixed
     */
    public function handle(array $input)
    {
        // dump($input);
        // 获取外部传递参数
        $informationId = $this->payload['id'] ?? null;
        $state         = $input['state'];
        logger('----------状态流转商机管理员提交数据----------', $input);
        if ($informationId) {
            switch ($state) {
                case GatherInformationState::STATE_PASS:
                    (new PassStateFlow())->run($informationId, Admin::user()->id, $input);
                    break;
                case GatherInformationState::STATE_READ:
                    (new ReadStateFlow())->run($informationId, Admin::user()->id, $input);
                    break;
                case GatherInformationState::STATE_ADD:
                    (new AddStateFlow())->run($informationId, Admin::user()->id, $input);
                    break;
                case GatherInformationState::STATE_DISCARD:
                    (new DiscardStateFlow())->run($informationId, Admin::user()->id, $input);
                    break;
            }
        }
        return $this
            ->response()
            ->success('操作成功！')
            ->refresh();
    }

    /**
     * Build a form here.
     */
    public function form()
    {

        // 也可以通过以下方式去底部元素
        $this->disableResetButton();
        // 获取外部传递参数
        $id   = $this->payload['id'] ?? null;
        $info = GatherInformation::query()->where('id', $id)->first();

        $this->radio('state', '操作')
            ->default(GatherInformationState::STATE_PASS)
            ->options([
                GatherInformationState::STATE_PASS    => '通过',
                GatherInformationState::STATE_READ    => '传阅',
                GatherInformationState::STATE_ADD     => '加办',
                GatherInformationState::STATE_DISCARD => '废弃',
            ])
            ->when([GatherInformationState::STATE_PASS, GatherInformationState::STATE_DISCARD], function (Form $form) {
                $form->text('remark');
            })
            ->when([GatherInformationState::STATE_READ, GatherInformationState::STATE_ADD], function (Form $form) {
                $form->multipleSelectTable('next_handler', '处理人')
                    //->title('请选择处理人')
                    ->from(AdminUserTable::make())
                    ->model(Administrator::class, 'id', 'name')
                    ->saveAsJson();
                $form->text('remark')->placeholder('请填写备注');
            })->required();

        if (empty($info->info_type)) {
            $this->radio('info_type')
                //->default(GatherInformationState::STATE_PASS)
                ->options(Common::INFO_TYPE)->required();
        }

        $this->hidden('handler');
        $this->hidden('is_add');
        $this->hidden('interval');
        $this->hidden('information_id');
    }

    /**
     * The data of the form.
     *
     * @return array
     */
    public function default()
    {
        // 获取外部传递参数
        $id = $this->payload['id'] ?? null;
        return [
            'state'          => GatherInformationState::STATE_PASS,
            'information_id' => $id,
            'remark'         => '通过'
        ];
    }

}
