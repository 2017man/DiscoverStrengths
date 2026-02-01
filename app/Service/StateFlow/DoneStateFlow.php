<?php


namespace App\Service\StateFlow;

use App\Models\CompanyConfig;
use App\Models\GatherInformationState;
use App\Models\Member;

/**
 * 已处理状态
 * Class InitStateFlow
 * @package App\Service\StateFlow
 */
class DoneStateFlow extends StateFlow
{
    protected $nextHandlerUsers;

    public function setState()
    {
        self::$state = GatherInformationState::STATE_DONE;
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
        if (!in_array(self::$latestStateInfo['state'], [GatherInformationState::STATE_DOING])) {
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
        // 查询商机管理员
        $nextHandlerUsers       = Member::getBusinessManager();
        $this->nextHandlerUsers = $nextHandlerUsers;
        self::$nextHandler      = array_values(array_column($nextHandlerUsers, 'id'));
    }


    /**
     * 业务处理前其他操作
     * @return mixed
     */
    public function handleBefore()
    {
        self::$SMS_NOTICE['sms_type']    = GatherInformationState::TYPE_WEB;
        self::$SMS_NOTICE['sms_mobiles'] = array_values(array_filter(array_column($this->nextHandlerUsers, 'phone')));
        //  XXX已处理xx单位收集信息，请去处理xxxxx
        $content                         =
            self::$handlerUser['name'] . '已处理' .
            self::$info['company_name']
            . '收集信息,请前去处理。';
        self::$SMS_NOTICE['sms_content'] = $content;

        self::$SMS_NOTICE['notice_type']    = GatherInformationState::TYPE_WEB;
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
