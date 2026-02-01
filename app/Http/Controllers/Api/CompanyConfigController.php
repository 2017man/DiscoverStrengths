<?php


namespace App\Http\Controllers\Api;


use App\Http\Requests\Api\CompanyConfigRequest;
use App\Models\CompanyConfig;

class CompanyConfigController extends Controller
{
    protected $isNeedLogin = false;

    public function companyByArea(CompanyConfigRequest $request)
    {
        $res      = [];
        $conCom   = [
            ['user_id', '>', 0],
            ['code', '<>', ''],
            ['area_code', '<>', ''],
        ];
        $companys = CompanyConfig::query()
            ->where($conCom)
            ->get(['area_code', 'area_name', 'code', 'name'])
            ->toArray();
        $companys = collect($companys)->groupBy('area_code')->toArray();
        foreach ($companys as $code => $company) {
            $children      = [];
            $item['text']  = $company[0]['area_name'] ?? '';
            $item['value'] = $code;
            foreach ($company as $companyChild) {
                $children[] = [
                    'text'  => $companyChild['name'],
                    'value' => $companyChild['code'],
                ];
            }
            $item['children'] = $children;
            $res[]            = $item;
        }
        return response()->json($res);
    }
}
