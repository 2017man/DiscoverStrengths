<?php


namespace App\Service\StateFlow;

use App\Models\CompanyConfig;
use App\Models\GatherInformationState;

/**
 * 处理中状态
 * Class InitStateFlow
 * @package App\Service\StateFlow
 */
class DoingStateFlow extends StateFlow
{
    // 吹里中不发短信
    public static $IS_SMS = false;

    public function setState()
    {
        self::$state = GatherInformationState::STATE_DOING;
    }

    /**
     * 逻辑性校验
     */
    public function checkLogic()
    {
        // 记录状态
        self::$latestStateInfo = array_pop(self::$stateLists);
        if (empty(self::$latestStateInfo)) {
            throw new \Exception('当前操作非法', '10000');
        };
        // 校验操作状态
        if (!in_array(self::$latestStateInfo['state'], [GatherInformationState::STATE_TODO])) {
            throw new \Exception('当前信息操作状态非法', '10000');
        }
        // 校验操作权限
        if (!in_array(self::$handler, json_decode(self::$latestStateInfo['next_handler'], true))) {
            throw new \Exception('当前用户操作非法', '10000');
        }
    }

    /**
     * 设置下一操作人
     */
    public function setNextHandler()
    {
        self::$nextHandler = self::$handler;
    }


    /**
     * 业务处理前其他操作
     * @return mixed
     */
    public function handleBefore()
    {

    }

    /**
     * 业务处理后其他操作
     * @return mixed
     */
    public function handleAfter()
    {

    }


}
