<?php


namespace App\Service\StateFlow;

use App\Models\GatherInformationState;
use App\Models\Member;
use App\Utils\ChinaMobileAaas\SMS;

/**
 * 审核传阅状态
 * Class InitStateFlow
 * @package App\Service\StateFlow
 */
class ReadStateFlow extends StateFlow
{
    protected $nextHandlerUsers;

    public function setState()
    {
        self::$state = GatherInformationState::STATE_READ;
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
        if (!in_array(self::$latestStateInfo['state'], [GatherInformationState::STATE_DONE])) {
            throw new \Exception('当前信息操作状态非法', '10000');
        }
        // 校验操作权限
        if (!in_array(self::$handler, json_decode(self::$latestStateInfo['next_handler'], true))) {
            throw new \Exception('当前用户操作非法', '10000');
        }
    }

    /**
     * TODO 设置下一操作人
     * 传阅人员
     */
    public function setNextHandler()
    {
        self::$nextHandler      = self::$data['next_handler'];
        $this->nextHandlerUsers = Member::query()->whereIn('id', self::$nextHandler)->get()->toArray();
    }


    /**
     *  给传阅人员发送信息
     * 业务处理前其他操作
     * @return mixed
     */
    public function handleBefore()
    {
        // 1.给客户经理发消息
        self::$SMS_NOTICE['sms_type'] = GatherInformationState::TYPE_APP;
        // TODO
        self::$SMS_NOTICE['sms_mobiles'] = array_values(array_filter(array_column($this->nextHandlerUsers, 'phone')));
        //  收到来自xxx传阅的xxx集团收集信息，请查阅！
        $content                         = '收到来自'
            . \Admin::user()->name
            . '审核传阅的'
            . self::$info['company_name']
            . '收集信息，请查阅！';
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
