<?php

namespace App\Http\Controllers;

use App\Models\UserDetail;
use App\Models\Users;
use Illuminate\Http\Request;
use Google\Cloud\Firestore\FirestoreClient;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Str;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    public $statusArray = ['1' => 'Active', '0' => 'Inactive'];

    /**
     * Return api success status
     * @param $status, $data, $message
     * @return json
     */
    protected function returnResponse($status = 200, $statusText = true, $message = "Good", $data = [], $validation_error = null)
    {
        $data['message'] = $message;
        $data['status'] = $statusText;
        if (!is_null($validation_error)) {
            $data['validation_error'] = $validation_error;
        }
        return response($data, $status);
    }

    /**
     * search in multi array
     */
    public function searcharray($value, $key, $array)
    {
        foreach ($array as $k => $val) {
            if ($val->$key == $value) {
                return $val;
            }
        }
        return null;
    }
    /**
     * @var initailizeFirebase
     */
    protected function initailizeFirebase()
    {
        $serviceAccount = __DIR__ . config('firebase.credentials.file');
        return new FirestoreClient([
            'keyFilePath' => $serviceAccount,
            'projectId' => config('firebase.credentials.project_id'),
        ]);
    }

    /**
     * @var ImageNameUnique
     */
    protected function nameToUnique($fileName, $limit = 15)
    {
        $extension = pathinfo($fileName, PATHINFO_EXTENSION);
        return Str::random($limit) . '.' . $extension;
    }


    function getUserIdFromToken(Request $request){
        if(auth()->check()){
            $userId = Auth::id();
        }
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
