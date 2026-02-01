<?php


namespace App\Http\Controllers\Api;


use App\Http\Requests\Api\HelperDictionaryRequest;
use App\Http\Service\HelperDictionaryService;

class HelperDictionaryController extends Controller
{
    protected $isNeedLogin = false;

    public function options(HelperDictionaryRequest $request)
    {
        $code    = $request->code;
        $service = new HelperDictionaryService();
        $result  = $service->getOptions($code);
        return response()->json($result);
    }

    public function optionsApi(HelperDictionaryRequest $request)
    {
        $code    = $request->code;
        $service = new HelperDictionaryService();
        $result  = $service->getOptionsApi($code);
        return response()->json($result);
    }

    public function optionsTextApi(HelperDictionaryRequest $request)
    {
        $code    = $request->code;
        $service = new HelperDictionaryService();
        $result  = $service->getOptionsTextApi($code);
        return response()->json($result);
    }
}
