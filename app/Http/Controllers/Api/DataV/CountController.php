<?php


namespace App\Http\Controllers\Api\DataV;


use App\Http\Controllers\Api\Controller;
use App\Http\Requests\Api\CompanyConfigRequest;
use App\Http\Requests\Api\FormRequest;
use App\Models\CompanyConfig;
use App\Models\GatherInformationCount;
use App\Models\ScreenLicence;
use Illuminate\Support\Facades\DB;

class CountController extends Controller
{
    protected $isNeedLogin = false;

    public function count(FormRequest $request)
    {
        $data = GatherInformationCount::query()
            ->select('user_department', DB::raw('SUM( num_infos ) num_infos,
	SUM( num_company ) num_company,
	SUM( num_relations ) num_relations '))
            ->groupBy('user_department')
            ->get()
            ->toArray();
        // 按照证照量排序
        array_multisort(array_column($data, 'num_infos'), SORT_DESC, $data);
        //array_walk($data, function (&$val) {
        //    $val['z_valid_num']  = round($val['z_valid_num'] / 10000);
        //    $val['z_signed_num'] = round($val['z_signed_num'] / 10000);
        //    $val['sign_ratio']   = substr($val['sign_ratio'], 0, mb_strlen($val['sign_ratio']) - 1);
        //});
        $result = [
            'xData'        => array_column($data, 'user_department'),
            'depData'      => array_column($data, 'num_company'),
            'infoData'     => array_column($data, 'num_infos'),
            'relationData' => array_column($data, 'num_relations'),
        ];
        return response()->json($result);
    }
}
