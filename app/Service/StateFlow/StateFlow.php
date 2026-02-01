<?php


namespace App\Service\StateFlow;


use App\Models\CompanyConfig;
use App\Models\GatherInformation;
use App\Models\GatherInformationState;
use App\Models\Member;
use App\Service\NotificationService;
use App\Service\Service;
use App\Utils\ChinaMobileAaas\SMS;

/**
 * 状态流转
 * Class StateFlow
 * @package App\Service
 */
abstract class StateFlow extends Service
{
    static $data = [];
    // 当前操作状态
    static $state;
    // 当前状态操作人
    static $handler;
    // 信息ID
    static $informationId;
    // 最新状态记录
    static $latestStateInfo;
    // 状态记录ID
    static $infoStateId;
    // 处理人
    static $info = [];
    // 状态列表
    static $stateLists = [];
    // 客户经理
    static $customerUser;
    // 处理人用户相关信息
    static $handlerUser = [];


    // 当前状态下一个操作人
    static $nextHandler = 0;

    // 当前状态下一个操作人
    static $mobiles;

    // 是否需要发送信息
    static $IS_SMS = true;

    static $SMS_NOTICE = [
        'sms_type'       => '',
        'sms_mobiles'    => [],
        'sms_content'    => '',
        'notice_type'    => '',
        //'notice_mobiles'    => [],
        'notice_content' => '',
    ];

    /**
     * 设置状态
     * @return mixed
     */
    public abstract function setState();

    /**
     * 逻辑性校验
     */
    public abstract function checkLogic();

    public abstract function setNextHandler();

    /**
     * 业务处理前其他操作
     * @return mixed
     */
    public abstract function handleBefore();

    /**
     * 业务处理后其他操作
     * @return mixed
     */
    public abstract function handleAfter();

    /**
     * 初始化参数
     */
    public function init($information_id, $handler, $data)
    {
        self::$data          = $data;
        self::$handler       = $handler;
        self::$informationId = $information_id;
        logger('--init-----', [$information_id, $handler, $data]);
    }

    /**
     * 数据校验
     * @throws \Exception
     */
    public function checkData()
    {
        if (!self::$handler || !self::$informationId) {
            throw new \Exception('参数缺失', 10000);
        }
        // 消息详情
        self::$info = GatherInformation::query()->where('id', self::$informationId)->first();
        if (empty(self::$info)) {
            throw new \Exception('参数非法', 10001);
        }
        self::$info = self::$info->toArray();
        logger('信息数据info', self::$info);
        // 状态列表
        self::$stateLists = GatherInformationState::query()->where('information_id', self::$informationId)->get()->toArray();
        // 操作人信息
        self::$handlerUser = Member::with('roles')->where('id', self::$handler)->first();
        if (empty(self::$handlerUser)) {
            throw new \Exception('用户不存在', 10002);
        }
        self::$handlerUser = self::$handlerUser->toArray();
        // 客户经理
        self::$customerUser = CompanyConfig::getCustomerManager(self::$info['company_code']);
        logger('客户经理', self::$customerUser);
        if (empty(self::$customerUser)) {
            throw new \Exception('收集信息参数缺失_客户经理缺失', 10003);
        }

    }


    /**
     * 初始化
     * @param $state
     * @param array $data
     */
    public function run($information_id, $handler, $data = [])
    {
        logger('参数内容-----', [$information_id, $handler, $data]);
        // 1.初始化参数
        $this->init($information_id, $handler, $data);
        // 设置初始化状态
        $this->setState();
        logger('--2.setState-----', [self::$state]);
        // 2.逻辑合法性校验
        $this->checkData();
        logger('--3.checkData-----', [$information_id, $handler, $data]);
        // 3.逻辑合法性校验
        $this->checkLogic();
        logger('--4.checkLogic-----', [self::$state]);
        // 4.设置下一个处理人
        $this->setNextHandler();
        logger('--5.setNextHandler-----', [self::$nextHandler]);
        // 5.业务处理前其他操作
        $this->handleBefore();
        logger('--6.handleBefore-----', [self::$nextHandler]);
        // 6.状态流转业务处理
        $this->handle();
        logger('--7.handle-----', [self::$nextHandler]);
        // 7.业务处理后其他操作
        $this->handleAfter();
        logger('--8.handleAfter-----', [self::$nextHandler]);

    }

    /**
     * 业务处理
     */
    public function handle()
    {
        // 5. 创建状态流转记录
        $this->createStateRecords();

        if (self::$IS_SMS) {
            // 6.发送短信
            $this->sendSms();
            // 7.通知相关人员
            $this->notification();
        }

        // 更新信息最新状态
        $this->updateInfoLatestState();
    }


    /**
     * 创建状态流转记录
     */
    public function createStateRecords()
    {
        $interval = 0;
        if (self::$latestStateInfo) {
            $interval = time() - strtotime(self::$latestStateInfo['created_at']);
        }
        // TODO 是否加办需要处理逻辑
        $isAdd         = GatherInformationState::IS_ADD_NO;
        $next_handlers = is_array(self::$nextHandler) ? self::$nextHandler : [self::$nextHandler];
        $records       = [
            'information_id' => self::$informationId,
            'state'          => self::$state,
            'handler'        => self::$handler,
            'next_handler'   => json_encode($next_handlers),
            'interval'       => $interval,
            'remark'         => self::$data['remark'] ?? '',
            'is_add'         => $isAdd,
            'created_at'     => date('Y-m-d H:i:s', time()),
            'updated_at'     => date('Y-m-d H:i:s', time()),
        ];

        $infoStateId       = GatherInformationState::query()->insertGetId($records);
        self::$infoStateId = $infoStateId;
    }

    /**
     * 发送短息
     */
    public function sendSms()
    {
        if (!empty(self::$SMS_NOTICE['sms_mobiles'])) {
            SMS::sendSMS(
                self::$SMS_NOTICE['sms_mobiles'],
                self::$SMS_NOTICE['sms_content'],
                self::$SMS_NOTICE['sms_type'] ?? '');
            logger('--*******发送短信********-----', [self::$SMS_NOTICE['sms_mobiles'],
                self::$SMS_NOTICE['sms_content'],
                self::$SMS_NOTICE['sms_type'] ?? '']);
        }
    }

    /**
     * 消息通知
     */
    public function notification()
    {
        if (!is_array(self::$nextHandler)) {
            self::$nextHandler = [self::$nextHandler];
        }
        foreach (self::$nextHandler as $nextHandlerVal) {
            NotificationService::create(
                self::$state,
                self::$SMS_NOTICE['notice_type'] ?? '',
                self::$informationId,
                self::$infoStateId,
                self::$handler,
                $nextHandlerVal,
                self::$SMS_NOTICE['notice_content'] ?? '');
        }

    }

    // 更新信息最新状态
    public function updateInfoLatestState()
    {
        GatherInformation::query()->where('id', self::$informationId)->update([
            'latest_state' => self::$state
        ]);
        logger('--*******更新最新state********-----', [
            'id'           => self::$informationId,
            'latest_state' => self::$state
        ]);
    }
}
