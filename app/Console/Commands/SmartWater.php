<?php

namespace App\Console\Commands;

use App\Constants\Aaaas;
use App\Constants\Common;
use App\Models\Water;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Tests\Models\User;

/**
 * 统计收集数据信息
 * Class CountInfo
 * @package App\Console\Commands
 */
class SmartWater extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:smart_water';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '每分钟定时生成全量收集信息统计数据';

    public $client;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $client       = new Client([
            'base_uri' => 'https://www.plydzhsw.com:9550/smartWater/api/',
            'timeout'  => 2.0,
            'verify'   => false,
            'headers'  => [
                'token' => '759e0fa47e2902c1a83c0d589cd46de40'
            ]
        ]);
        $this->client = $client;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->aa();
    }

    public function aa()
    {
        $waters = Water::query()
            //->where('id','>=', 455)
            //->where('id','<=', 634)
                ->whereNull('msg')
            ->get()->toArray();
        foreach ($waters as $water) {
            $msg = '成功';
            try {
                $data     = [
                    "customerStatus" => "1",
                    "keyWord"        => $water['tel'],
                    "imei"           => '',
                    "arrears"        => '',
                    "regionId"       => 40,
                    "queryCondition" => ["currentPage" => 1, "pageSize" => 10, "total" => 1],
                ];
                $response = $this->client->request('POST', 'base/customer/getCustomerList4Manage', [
                    'json' => $data
                ]);
                $res      = $response->getBody()->getContents();
                $res      = json_decode($res, true);
                if ($res['resultCode'] !== 0) {
                    $msg = $res['message'] ?? '发生错误';
                }
                if (count($res['data']['rows'])) {
                    $user = $res['data']['rows'][0];
                } else {
                    $msg = '用户不存在';
                }

                dump($user);
                dump('用户------------------------------');

                if (!empty($user)) {
                    // 查询IMEI
                    $device    = [];
                    $data      = [
                        "keyWord"        => $water['imei'],
                        "regionId"       => 46,
                        "queryCondition" => ["currentPage" => 1, "pageSize" => 10, "total" => 1],
                    ];
                    $response2 = $this->client->request('POST', 'device/device/getWmMetersSelector', [
                        'json' => $data
                    ]);
                    $res2      = $response2->getBody()->getContents();
                    $res2      = json_decode($res2, true);
                    if ($res['resultCode'] !== 0) {
                        $msg = $res['message'] ?? '发生错误';
                    } else {
                        if (count($res2['data']['rows'])) {
                            $device = $res2['data']['rows'][0];
                        } else {
                            $msg = '设备已绑定';
                        }
                    }
                    dump($device);
                    dump('设备------------------------------');

                    if (!empty($device)) {
                        // 开卡
                        $data = [
                            "devType"      => 0,
                            "devId"        => $water['imei'],
                            "priceTypeId"  => 2,
                            "paymentType"  => 1,
                            "startReading" => $water['ds'],
                            "customerId"   => $user['id'],
                            "id"           => $user['id']
                        ];

                        $response = $this->client->request('POST', 'base/customer/addDevice', [
                            'json' => $data
                        ]);
                        $res3     = $response->getBody()->getContents();
                        $res3     = json_decode($res3, true);
                        if ($res3['resultCode'] !== 0) {
                            $msg = $res3['message'] ?? '发生错误';
                        }
                        dump($res3);
                        dump('开表------------------------------');
                    }

                }

                Water::query()->where('id', $water['id'])->update(['msg' => $msg]);

            } catch (ClientException $exception) {
                $status = Common::STATUS_ERROR;
                $msg    = $exception->getMessage();
                dump($msg);
            }
        }


    }

}
