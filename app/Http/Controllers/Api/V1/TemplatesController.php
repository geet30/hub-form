<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//----------
use App\Models\Template;
use App\Services\Template as TemplateService;

class TemplatesController extends Controller
{
  public function __construct() {
      $this->TemplateService = new TemplateService();
  }

  public function index($name = null, Request $request){
    try {
        // $user = auth()->user();
        $data = [];
        $data = $this->TemplateService->getListing($name, $request);

        $status = HTTP_STATUS_OK;
        $message = HTTP_SUCCESS;
        if(empty($data)) {
          $status = HTTP_NOT_FOUND;
          $data = 'Template not found';
        }

        return response()->json([
        'status' => $status,
        'response' => $data,
        ],$status);

    } catch (\Exception $e) {
        return response()->json([
        'response'=>$e->getMessage()
        ],HTTP_STATUS_SERVER_ERROR);
    }
  }

  public function show($id = null){
    try {
      // die('==show=');
        $data = [];
        $data = $this->TemplateService->getTemplateDetail($id);
        $data = $data->toArray();
        // echo "<pre>";print_r($data);die('===');

        $status = HTTP_STATUS_OK;
        $message = HTTP_SUCCESS;
        if(empty($data)) {
          $status = HTTP_NOT_FOUND;
          $data = 'Template not found';
        }

        return response()->json([
        'status' => $status,
        'response' => $data,
        ],$status);

    } catch (\Exception $e) {
        return response()->json([
        'response'=>$e->getMessage()
        ],HTTP_STATUS_SERVER_ERROR);
    }
  }
}
