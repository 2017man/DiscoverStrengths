<?php


namespace App\Service\StateFlow;

use App\Models\GatherInformationState;

/**
 * 初始化状态
 * Class InitStateFlow
 * @package App\Service\StateFlow
 */
class InitStateFlow extends StateFlow
{
    // 不发短信
    public static $IS_SMS = false;

    public function setState()
    {
        self::$state = GatherInformationState::STATE_INIT;
    }

    /**
     * 逻辑性校验
     */
    public function checkLogic()
    {
        if (count(self::$stateLists) > 0) {
            throw new \Exception('当前操作非法', '10000');
        }
        // 是否有操作权限
        // 无记录
        self::$latestStateInfo = [];
    }

    /**
     * 设置下一操作人
     * 集团单位客户经理
     */
    public function setNextHandler()
    {
        self::$nextHandler = self::$customerUser['id'];
    }


    /**
     * 业务处理前其他操作
     * @return mixed
     */
    public function handleBefore()
    {
        self::$data['remark'] = self::$data['content'];
    }

    /**
     * 业务处理后其他操作
     * @return mixed
     */
    public function handleAfter()
    {
        (new TodoStateFlow())->run(
            self::$informationId,
            self::$customerUser['id'],
            []);
    }


}
