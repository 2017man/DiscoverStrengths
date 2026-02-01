<?php


namespace App\Constants;


class Common
{
    /**
     * 返回状态
     * 0 - 失败
     * 1 - 成功
     */
    const STATUS_ERROR = 0;
    const STATUS_SUCCESS = 1;
    const INFO_TYPE = [
        '业务需求' => '业务需求',
        '服务需求' => '服务需求',
        '竞争信息' => '竞争信息',
        '其他信息' => '其他信息',
    ];

}
