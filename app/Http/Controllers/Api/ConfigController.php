<?php

namespace App\Http\Controllers\Api;

use App\Models\StrengthsSiteConfig;
use Illuminate\Http\Request;

/**
 * 站点配置接口
 */
class ConfigController extends Controller
{
    public function __construct()
    {
        $this->isNeedLogin = false;
        parent::__construct();
    }

    /**
     * 站点配置（首页）
     * GET /api/v1/config/site
     * 返回 stats_count、stats_date、qrcode_wechat、qrcode_community
     */
    public function site(Request $request)
    {
        $config = StrengthsSiteConfig::query()->first();
        if (!$config) {
            $data = [
                'stats_count' => '0',
                'stats_date' => '2014年5月19日 ~ 至今',
                'qrcode_wechat' => null,
                'qrcode_community' => null,
            ];
        } else {
            $data = [
                'stats_count' => (string) ($config->stats_count ?? '0'),
                'stats_date' => (string) ($config->stats_date ?? '2014年5月19日 ~ 至今'),
                'qrcode_wechat' => $config->qrcode_wechat ? (string) $config->qrcode_wechat : null,
                'qrcode_community' => $config->qrcode_community ? (string) $config->qrcode_community : null,
            ];
        }

        return $this->success($data);
    }
}
