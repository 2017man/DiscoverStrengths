<?php


namespace App\Service;


use App\Models\GatherInformationNotification;

class NotificationService extends Service
{
    // 创建通知记录
    // 获取个人通知列表
    // 通知已读
    // 通知处理
    public static function create($state, $type, $informationId, $informationStateId, $handler, $nextHandler, $content = '')
    {
        $records   = [
            'information_id'       => $informationId,
            'information_state_id' => $informationStateId,
            'state'                => $state,
            'type'                 => $type,
            'handler'              => $handler,
            'next_handler'         => $nextHandler,
            'read_at'              => '',
            'content'              => $content,
            'created_at'           => date('Y-m-d H:i:s', time()),
            'updated_at'           => date('Y-m-d H:i:s', time()),
        ];
        $recordsId = GatherInformationNotification::query()->insert($records);
        return $recordsId;
    }

    /**
     * 查看
     * @param $notificationId
     */
    public static function read($notificationId)
    {
        GatherInformationNotification::query()->where('id', $notificationId)->update([
            'read_at' => date('Y-m-d H:i:s'),
        ]);
    }

}
