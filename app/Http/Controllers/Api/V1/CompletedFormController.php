<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Locations;
use App\Models\Template;
use App\Models\CompletedForm;
//----------
use App\Services\CompletedForm as CompletedFormService;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;

// use Symfony\Component\HttpFoundation\Response;

class CompletedFormController extends Controller
{
    public function __construct()
    {
        $this->completedFormService = new CompletedFormService();
    }

    public function index($form_status = null, $title = null, Request $request)
    {
        try {
            // $user = auth()->user();
            $data = [];
            if(auth()->user()->users_type=="supplier"){
                $data = $this->completedFormService->getListing($form_status, $title, $request);
            }else{
                $data = $this->completedFormService->getListingByRole($form_status, $title, $request);
            }
            $status = HTTP_STATUS_OK;
            $message = HTTP_SUCCESS;
            if (empty($data)) {
                $status = HTTP_NOT_FOUND;
                $data = 'Completed Forms not found';
            }
            return response()->json([
                'status' => $status,
                'data' => $data,
            ], $status);
        } catch (\Exception $e) {
            return response()->json([
                'data' => $e->getMessage(),
            ], HTTP_STATUS_SERVER_ERROR);
        }
    }

    /**
     * get data for map
     */
    public function getMapData(Request $request)
    {
        try {
            $data = [];
            $where = [];
            if(auth()->user()->users_type=="supplier"){
                if ($request->has('user_id') && !empty($request->user_id)) {
                    $where = ['user_id' => $request->user_id];
                }
            }else{
                $roleId=auth()->user()->users_details->i_ref_user_role_id;
                if ($request->has('user_id') && !empty($request->user_id)) {
                    $where = ['i_ref_user_role_id' => $roleId];
                }
            }


            $data = $this->completedFormService->getMapData($where, ['id', 'title', 'location_name', 'latitude', 'longitude', 'user_id', 'status', 'template_id'], ['Template']);
            $status = HTTP_STATUS_OK;
            $message = HTTP_SUCCESS;
            if (empty($data)) {
                $status = HTTP_NOT_FOUND;
                $data = 'Completed Forms not found';
            }
            return response()->json([
                'status' => $status,
                'data' => $data,
                'colors' => Template::allColors
            ], $status);
        } catch (\Exception $e) {
            return response()->json([
                'data' => $e->getMessage(),
            ], HTTP_STATUS_SERVER_ERROR);
        }
    }

    public function show($id = null)
    {
        try {
            // $user = auth()->user();
            $data = [];
            $data = $this->completedFormService->getCompleteFormDetail($id);
            $status = HTTP_STATUS_OK;
            $message = HTTP_SUCCESS;
            if (empty($data)) {
                $status = HTTP_NOT_FOUND;
                $data = 'Completed Form not found';
            }
            return response()->json([
                'status' => $status,
                'data' => $data,
            ], $status);
        } catch (\Exception $e) {
            return response()->json([
                'data' => $e->getMessage(),
            ], HTTP_STATUS_SERVER_ERROR);
        }
    }

    public function save_form(Request $request)
    {
        try {
            // $user = auth()->user();
            $data = [];
            $data = $this->completedFormService->saveCompleteFormData($request);

            if (!empty($data) && isset($data['message']) && isset($data['completed_form_id']) && $data['message'] == 'saved' && $data['completed_form_id'] != '') {
                $status = HTTP_STATUS_OK;
                $message = HTTP_SUCCESS;
                $data = array('message' => 'Completed Forms saved successfully!', 'completed_form_id' => $data['completed_form_id'], 'form_id' => $data['form_id'], 'form_title'=>$data['form_title']);
            } else {
                $status = HTTP_NOT_FOUND;
                $data = 'Completed Forms not saved!';
            }

            return response()->json([
                'status' => $status,
                'data' => $data,
            ], $status);
        } catch (\Exception $e) {
            return response()->json([
                'data' => $e->getMessage(),
            ], HTTP_STATUS_SERVER_ERROR);
        }
    }

    public function edit_form(Request $request)
    {
        try {
            // $user = auth()->user();
            $data = [];
            $data = $this->completedFormService->editCompleteFormData($request);

            if (!empty($data) && isset($data['message']) && isset($data['completed_form_id']) && $data['message'] == 'saved' && $data['completed_form_id'] != '') {
                $status = HTTP_STATUS_OK;
                $message = HTTP_SUCCESS;
                $data = array('message' => 'Completed Forms saved successfully!', 'completed_form_id' => $data['completed_form_id'], 'form_id' => $data['form_id'], 'form_title'=>$data['form_title']);
            } elseif (!empty($data) && $data == 'form_id_required') {
                $status = HTTP_NOT_FOUND;
                $data = 'Completed Form id is null!';
            } else {
                $status = HTTP_NOT_FOUND;
                $data = 'Completed Forms not saved!';
            }

            return response()->json([
                'status' => $status,
                'data' => $data,
            ], $status);
        } catch (\Exception $e) {
            return response()->json([
                'data' => $e->getMessage(),
            ], HTTP_STATUS_SERVER_ERROR);
        }
    }

    public function upload_file(Request $request)
    {
        try {
            // $user = auth()->user();
            $data = [];
            $data = $this->completedFormService->uploadMediaFiles($request);

            if (!empty($data)) {
                $status = HTTP_STATUS_OK;
                $message = HTTP_SUCCESS;
                $data = $data;
            } else {
                $status = HTTP_NOT_FOUND;
                $data = 'File is not uploaded!';
            }

            return response()->json([
                'status' => $status,
                'data' => $data,
            ], $status);
        } catch (\Exception $e) {
            return response()->json([
                'data' => $e->getMessage(),
            ], HTTP_STATUS_SERVER_ERROR);
        }
    }

    public function delete_media(Request $request)
    {
        try {
            // $user = auth()->user();
            $data = [];
            $data = $this->completedFormService->delete_media($request);

            if (!empty($data) && $data == 'deleted') {
                $status = HTTP_STATUS_OK;
                $message = HTTP_SUCCESS;
                $data = 'Media deleted successfully';
            } else {
                $status = HTTP_NOT_FOUND;
                $data = 'Media cannot be deleted!';
            }

            return response()->json([
                'status' => $status,
                'data' => $data,
            ], $status);
        } catch (\Exception $e) {
            return response()->json([
                'data' => $e->getMessage(),
            ], HTTP_STATUS_SERVER_ERROR);
        }
    }

    public function share_form(Request $request, $id)
    {
        try {
            // $user = auth()->user();
            $data = [];
            $data = $this->completedFormService->shareCompletedForm($request, $id);

            if (!empty($data)) {
                $status = HTTP_STATUS_OK;
                $message = HTTP_SUCCESS;
                $pdf = PDF::loadView('pdf.complete_form_report', $data);
                $fileName = $this->nameToUnique("test.pdf");
                
                if($pdf->save(public_path('completed_form').'/'.$fileName)){
                    return  response()->json(['link' => url('completed_form/'. $fileName)]);
                }else{
                    return 'No File Found!';
                }
            } else {
                $status = HTTP_NOT_FOUND;
                $data = 'File doesnot exist!';
                return response()->json([
                    'status' => $status,
                    'data' => $data,
                ], $status);
            }

        } catch (\Exception $e) {
            return response()->json([
                'data' => $e->getMessage(),
            ], HTTP_STATUS_SERVER_ERROR);
        }
    }

    public function formData(Request $request)
    {
        try {
            $response = [];
            $locations = Locations::get();
            $response['locations'] = $locations;
            return $this->returnResponse(HTTP_STATUS_OK, true, trans('response.success'), $response);
        } catch (Exception $ex) {
            return $this->returnResponse(HTTP_STATUS_SERVER_ERROR, false, $ex->getMessage());
        }
    }

    public function deleteForm($id){
        try{
            $data = CompletedForm::find($id);
            $data->delete();
            return $this->returnResponse(HTTP_STATUS_OK, true, 'Completed Form deleted successfully.');
        }catch(Exception $ex) {
            return $this->returnResponse(HTTP_STATUS_SERVER_ERROR, false, $ex->getMessage());
        }
    }

    /**
     * get all the
     * actions of form 
     */
    public function form_action_data($id = null)
    {
        try {
            // $user = auth()->user();
            $data = [];
            $data = $this->completedFormService->form_action_data($id);
            $status = HTTP_STATUS_OK;
            $message = HTTP_SUCCESS;
            if (empty($data)) {
                $status = HTTP_NOT_FOUND;
                $data = 'Completed Form not found';
            }
            return response()->json([
                'status' => $status,
                'data' => $data,
            ], $status);
        } catch (\Exception $e) {
            return response()->json([
                'data' => $e->getMessage(),
            ], HTTP_STATUS_SERVER_ERROR);
        }
    }
}
