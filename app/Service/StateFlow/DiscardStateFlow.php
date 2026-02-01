<?php


namespace App\Service\StateFlow;

use App\Models\GatherInformationState;
use App\Models\Member;
use App\Utils\ChinaMobileAaas\SMS;

/**
 * 审核废弃状态
 * Class InitStateFlow
 * @package App\Service\StateFlow
 */
class DiscardStateFlow extends StateFlow
{
    protected $nextHandlerUsers;

    public function setState()
    {
        self::$state = GatherInformationState::STATE_DISCARD;
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
     * 设置下一操作人
     */
    public function setNextHandler()
    {
    }


    /**
     *  需要给整个链条相关前置人员发送信息
     * 业务处理前其他操作
     * @return mixed
     */
    public function handleBefore()
    {
        // 1.给客户经理发消息
        self::$SMS_NOTICE['sms_type']    = GatherInformationState::TYPE_APP;
        self::$SMS_NOTICE['sms_mobiles'] = [self::$customerUser['phone']];
        //  您处理的xx单位信息已审核通过，感谢您的积极处理！
        $content                         = '您处理的' .
            self::$info['company_name']
            . '信息已废弃，感谢您的积极处理！';
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
        // 2.给收集人员发消息
        //  您收集的xx单位信息已审核通过，感谢您的反馈！
        $content   = '您收集的' .
            self::$info['company_name']
            . '信息已废弃，感谢您的反馈！';
        $startUser = Member::userInfo(self::$info['user_id']);
        logger('发起人信息', $startUser);
        // 发送消息
        SMS::sendSMS($startUser['phone'], $content, self::$state);
    }


}
