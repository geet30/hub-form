<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * return file name
     * of terms and condition
     */
    public function terms_condition(){
        try {

            $terms_cond = Setting::where('type', 1)->first();

            $data = !empty($terms_cond) ? $terms_cond->file : '';
           
            $status = HTTP_STATUS_OK;
            $message = HTTP_SUCCESS;
            if(empty($data)) {
              $status = HTTP_NOT_FOUND;
              $data = 'No File Found!';
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


    /**
     * return file name of 
     * privacy and policy 
     */
    public function privacy_policy(){
    try {
        
        $privacy_policy = Setting::where('type', 2)->first();
        $data = !empty($privacy_policy) ? $privacy_policy->file : '';
        
        $status = HTTP_STATUS_OK;
        $message = HTTP_SUCCESS;
        if(empty($data)) {
            $status = HTTP_NOT_FOUND;
            $data = 'No File Found!';
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
