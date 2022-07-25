<?php

namespace App\Http\Controllers\Api\V1;
use Storage;
use App\Http\Controllers\Controller;
use App\Models\RolesFormPermissions;
use App\Models\UserDetail;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    //
    /**
     * Get user permission for form builder
     * @param Request $Request()
     */
    public function permissions(Request $Request)
    {
        try {
            $retData = [];
            $userId = $Request['user_id'];
            $UserDetail = UserDetail::select('i_ref_role_id')->where('i_ref_user_id', $userId)->firstOrFail();
            $roleId = $UserDetail->i_ref_role_id;
            $retData['permissions'] = RolesFormPermissions::with([
                'form_permission' => function ($query) {
                    $query->select('id', 'vc_name');
                },
            ])->select('form_permission_id')->where('role_id', $roleId)->get();
            return $this->returnResponse(Response::HTTP_OK, true, trans('response.success'), $retData);
        } catch (Exception $ex) {
            return $this->returnResponse(Response::HTTP_INTERNAL_SERVER_ERROR, false, $ex->getMessage());
        }
    }

    /**
     * check credentials in hubform
     * for login in p2b
     * @param Request $Request()
     */
    public function p2b_login(Request $request)
    {
        try {
            $data = [];
            if(!empty($request)){
                $data['User'] = Users::where('email', $request->email)->where('user_type', $request->user_type)->first();

                if (!empty($data['User']) && Hash::check($request->password, $data['User']->hash_password)) {
                    return $this->returnResponse(Response::HTTP_OK, true, trans('response.success'), $data);
                }else{
                    $data['User'] = '';
                    return $this->returnResponse(Response::HTTP_OK, false, trans('response.success'), $data);
                }
            }
        } catch (Exception $ex) {
            return $this->returnResponse(Response::HTTP_INTERNAL_SERVER_ERROR, false, $ex->getMessage());
        }
    }


    public function updateprofile(Request $request){
        $image = !empty($request->file('image')) ? $request->file('image') : '';
        $imageName = '';
        $path = '';
        try {
            //upload file in s3 bucket
            if (isset($image) && $image !== '') {
                $imageName = time() . '.' . $image->extension();
                $filePath =  $imageName;
                // $upload = Storage::disk('s3')->put($filePath, file_get_contents($image));
                $path = Storage::disk('s3')->put('users', $request->image);
                // $image_path = Storage::disk('s3')->url($path);
            
            }
            $input['vc_image'] = $path;
            $userRow = Users::findOrFail($request->id);
            $userRow->update($input);
            $userRow = Users::findOrFail($request->id);
            
                $status = HTTP_STATUS_OK;
                $message = HTTP_SUCCESS;
                if (empty($userRow->image_url)) {
                    $status = HTTP_STATUS_NOT_FOUND;
                    $data = 'User not found';
                }
                return response()->json([
                    'status' => $status,
                    'data' => $userRow->image_url,
                ], $status);

        } catch (Exception $ex) {
            return $this->returnResponse(Response::HTTP_INTERNAL_SERVER_ERROR, false, $ex->getMessage());
        }
    }


    public function get_profile_image(Request $request){
        try {
            $userRow = Users::findOrFail($request->id);
            
            $status = HTTP_STATUS_OK;
            if (empty($userRow->image_url)) {
                $status = HTTP_STATUS_NOT_FOUND;
                $data = 'User not found';
            }
            return response()->json([
                'status' => $status,
                'data' => $userRow->image_url,
            ], $status);

        } catch (Exception $ex) {
            return $this->returnResponse(Response::HTTP_INTERNAL_SERVER_ERROR, false, $ex->getMessage());
        }
    }



}
