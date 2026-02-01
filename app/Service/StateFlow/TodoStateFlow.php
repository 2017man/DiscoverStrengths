<?php


namespace App\Service\StateFlow;

use App\Models\GatherInformationState;

/**
 * 待处理状态
 * Class InitStateFlow
 * @package App\Service\StateFlow
 */
class TodoStateFlow extends StateFlow
{

    public function setState()
    {
        self::$state = GatherInformationState::STATE_TODO;
    }

    /**
     * 逻辑性校验
     */
    public function checkLogic()
    {
        if (count(self::$stateLists) > 1) {
            throw new \Exception('当前操作非法', '10000');
        }
        // 是否有操作权限(此处不用校验)
        // 有一条记录
        self::$latestStateInfo = array_pop(self::$stateLists);
        if (empty(self::$latestStateInfo)) {
            throw new \Exception('当前操作非法', '10000');
        };
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
        self::$SMS_NOTICE['sms_type']    = GatherInformationState::TYPE_APP;
        self::$SMS_NOTICE['sms_mobiles'] = self::$handlerUser['phone'];
        // TODO XXX去xx收集了xx单位信息，请去处理xxxxx
        $content                         = self::$info['company_name']
            . '有一条收集信息,请前去处理。'
            . env('H5_PAGE_INFO_DETAIL') . self::$informationId;
        self::$SMS_NOTICE['sms_content'] = $content;

        self::$SMS_NOTICE['notice_type']    = GatherInformationState::TYPE_APP;
        self::$SMS_NOTICE['notice_content'] = $content;
    }

    /**
     * 业务处理后其他操作
     * @return mixed
     */
    public function handleAfter()
    {

    }


}
