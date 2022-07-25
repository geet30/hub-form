<?php

use App\Models\Role;
use App\Models\Users;
use App\Models\UserDetail;
use Illuminate\Http\Request;

if (!function_exists('encrypt_decrypt')) {

    function encrypt_decrypt($action, $string)
    {
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $secret_key = env('SECRET_KEY', 'wiw3g716qXYY29HUzzdOtvSfNkb7n5PN');
        $secret_iv = env('SECRET_IV', 'kIksnotLbVZ71hW4mtnL4RFSyar3l6a8');
        // hash
        $key = hash('sha256', $secret_key);
        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash('sha256', $secret_iv), 0, 16);

        if ($action == 'encrypt') {
            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
        } else if ($action == 'decrypt') {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }
        return $output;
    }
}

if (!function_exists('returnResponse')) {
    /**
     * Return api success status
     * @param $status, $data, $message
     * @return json
     */
    function returnResponse($status = 200, $statusText = true, $message = "Good", $data = [], $validation_error = null)
    {
        $data['message'] = $message;
        $data['status'] = $statusText;
        if (!is_null($validation_error)) {
            $data['validation_error'] = $validation_error;
        }
        return response($data, $status);
    }


    function get_actions($user_id){

       $data= App\Models\Action::get_actions_for_chat($user_id);
        // dd($data);
        $room_ids=[];
        foreach($data as $key => $value){
            $room_ids[]="room_".$value->id;
        }
        return $room_ids;
        // dd($room_ids);
    }


}

if (!function_exists('CheckUserTypeAndGetRoleID')) {

    function CheckUserTypeAndGetRoleID($id){
        $user=Users::with(['user_device_token'])->find($id);
        // dd($user->full_name);
        if(empty($user)){
            $user['user_type']="";
            $user['vc_fname']="";
            $user['vc_fname']="";
            $user['vc_mname']="";
            $user['vc_lname']="";
            $user['name']="";
            $user['id']="";
            $user['user_id']="";
            $user['full_name']="";
            $user['role_id']="";
            $user['user_device_token']="";  
            $user['role_name']="";   
            return $user;        
        }
        $user['user_type']=$user->user_type;
        $user['vc_fname']=$user->vc_fname;
        $user['vc_mname']=$user->vc_mname;
        $user['vc_lname']=$user->vc_lname;
        $user['name']=$user->full_name;
        $user['id']=$user['id'];
        $user['user_id']=$user['id'];
        $user['full_name']=$user->full_name;
        $user['role_id']="";
        $user['user_device_token']=""; 
        if(!empty($user->user_device_token)){
            $user['user_device_token']=$user->user_device_token->device_token;
        }
         //supplier //company
        $UserDetail=UserDetail::with(['roles'])->where("i_ref_user_id",$id)->first();     
        //    dd($UserDetail);
    
        if(!empty($UserDetail->i_ref_role_id) || isset($UserDetail->i_ref_role_id)){
            //compnay
           
            $user['role_id']=0;
            // $user['role_name']="compnay_login_role";
            if($UserDetail->i_ref_role_id!=0){
                //employee
                // dd($UserDetail);
                $user['role_id']=$UserDetail->i_ref_role_id;
                
                $user['role_name']=$UserDetail->roles->vc_name;
                // $role['full_name']=$user->full_name;  
            }
        }else{
            // dd("adsa");
            //supplier
            $user['role_id']="";
            // $user['role_name']="supplier_login_role";
                   
        }

        return $user; 

    }

}


if (!function_exists('role_has_user')) {

    function role_has_user($id){
        $role=[];
        $role['user_type']="";
        $role['user']="";
        $role['role_id']="";
        $role['user_id']="";
        $role['full_name']="";
        $role['user_device_token']="";
        $role['vc_fname']="";
        $role['vc_mname']="";
        $role['vc_lname']="";
        $role['role_name']=""; 
        $role['id']="";
        // dd($id);
        if(!empty($id) || isset($id)){
            if($id!=0){
                //employee
                $Role=Role::with(['user_detail.user.user_device_token'])->find($id);             
                if(!empty($Role->user_detail) && !empty($Role->user_detail->user)){
                    $role['role_id']=$Role->id;
                    $role['role_name']=$Role->vc_name;
                    $role['id']=$Role->user_detail->user->id;
                    $role['user_id']=$Role->user_detail->user->id;
                    $role['user_device_token']="";
                    $role['vc_fname']=$Role->user_detail->user->vc_fname;
                    $role['vc_mname']=$Role->user_detail->user->vc_mname;
                    $role['vc_lname']=$Role->user_detail->user->vc_lname;
                    $role['user']=$Role->user_detail->user;
                    $role['full_name']=$Role->user_detail->user->full_name;  
                    if(!empty($Role->user_detail->user->user_device_token)){
                        $role['user_device_token']=$Role->user_detail->user->user_device_token->device_token;
                    }
                    // dd($role);
                    return $role;
                }else{
                    $role['role_id']=$Role->id;
                    $role['role_name']=$Role->vc_name;
                    $role['user_id']="";
                    $role['full_name']="";
                    $role['user_device_token']="";
                    // dd($role);
                    return $role;
                }
            }
            // company
            $company_id=auth()->user()->users_details->i_ref_company_id;
            $id=UserDetail::select("i_ref_user_id")->Where('i_ref_company_id',$company_id)->where("i_ref_role_id",0)->first();
            $user=Users::with(['users_details','user_device_token'])->find($id->i_ref_user_id);
            
            $role['user_type']="company";
            $role['user']=$user;
            $role['role_id']=0;
            $role['id']=$user->id;
            $role['user_id']=$user->id;
            $role['full_name']=$user->full_name;    
            $role['vc_fname']=$user->vc_fname;
            $role['vc_mname']=$user->vc_mname;
            $role['vc_lname']=$user->vc_lname;
            $role['user_device_token']="";
            if(!empty($user->user_device_token)){
                $role['user_device_token']=$user->user_device_token->device_token;
            }
            return $role;
        }else{
            //supplier
            return $role;
        }

    }

}


if (!function_exists('CheckUserType')) {

    function CheckUserType($roleid=null,$user_id=null){
        
        $user=Users::with(['user_device_token'])->withTrashed()->find($user_id);
        if($user->user_type == "supplier"){
            return CheckUserTypeAndGetRoleID($user_id); //user id
        }else{
            if($roleid==0 && empty($roleid)){               
                return CheckUserTypeAndGetRoleID($user_id); //user id
            }

            return role_has_user($roleid);
        }
    }
}

if (!function_exists('getUserIdFromToken')) {

    function getUserIdFromToken(Request $request){
        $userId = Auth::id();
        if(empty($userId) && $request->header('Token')){
            $token = $request->header('Token');
            $tokenParts = explode(".", $token);
            $tokenHeader = base64_decode($tokenParts[0]);
            $tokenPayload = base64_decode($tokenParts[1]);
            $jwtHeader = json_decode($tokenHeader);
            $jwtPayload = json_decode($tokenPayload);
            $userId = $jwtPayload->id;
        }
        return $userId;
    }
}
