<?php


namespace App\Utils\ChinaMobileAaas;

use App\Constants\Aaaas;
use App\Constants\Common;
use App\Models\SmsSendRecord;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

/**
 * 智慧总台
 * 短信发送
 * Class SMS
 * @package App\Utils\ChinaMobileAaas
 */
class SMS extends Base
{
    /**
     * 批量发短信
     * 场景：多个手机号同一个短信内容
     * @param array $mobiles
     * @param string $content
     * @param string $type
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function sendSMS($mobiles = [], $content = '短信内容', $type = '')
    {
        if (!is_array($mobiles)) {
            $mobiles = [$mobiles];
        }
        $status      = Common::STATUS_SUCCESS;
        $msg         = 'success';
        $client      = new Client([
            // Base URI is used with relative requests
            'base_uri' => Aaaas::SMS_URL,
            // You can set any number of default request options.
            'timeout'  => 2.0,
        ]);
        $data        = [
            "ecName"    => Aaaas::SMS_ECNAME,
            "apId"      => Aaaas::SMS_APP_ID,
            "mobiles"   => implode(",", $mobiles),
            "content"   => (string)$content,
            "sign"      => Aaaas::SMS_SIGN,
            "addSerial" => "",
        ];
        $data['mac'] = md5($data['ecName']
            . $data['apId']
            . Aaaas::SMS_APP_SECRET
            . $data['mobiles']
            . $data['content']
            . $data['sign']
            . $data['addSerial']);
        $data        = base64_encode(json_encode($data));
        try {
            $response = $client->request('POST', '', ['body' => $data]);
            $res      = $response->getBody()->getContents();
            $res      = json_decode($res, true);
            if (empty($res['success']) || !$res['success']) {
                $status = Common::STATUS_ERROR;
                $msg    = $res['rspcod'] ?? '短信发送失败';
            }
        } catch (ClientException $exception) {
            $status = Common::STATUS_ERROR;
            $msg    = $exception->getMessage();
        }
        $records = [
            'phone'      => json_encode($mobiles),
            'content'    => $content,
            'type'       => $type,
            'send_state' => $status,
            'send_msg'   => $msg,
            'created_at' => date('Y-m-d H:i:s', time()),
            'updated_at' => date('Y-m-d H:i:s', time()),
        ];
        // 插入短信发送记录
        SmsSendRecord::query()->insert($records);
    }
}
