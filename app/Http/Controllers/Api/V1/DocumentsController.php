<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
//----------
use App\Services\Document as DocumentService;
use Illuminate\Http\Request;

class DocumentsController extends Controller
{
    public function __construct()
    {
        $this->DocumentService = new DocumentService();
    }

    public function index($name = null, Request $request)
    {
        try {
            // $user = auth()->user();
            $data = [];

            $where = [
                ['Use_in_mobile', true],
            ];
            if(auth()->user()->user_type="supplier"){

                $data = $this->DocumentService->getListing($name, $request, $where);
            }else{
                $data = $this->DocumentService->getListingByRole($name, $request, $where);
            }


            $status = HTTP_STATUS_OK;
            $message = HTTP_SUCCESS;
            if (empty($data)) {
                $status = HTTP_NOT_FOUND;
                $data = 'Document not found';
            }

            return response()->json([
                'status' => $status,
                'response' => $data,
            ], $status);

        } catch (\Exception $e) {
            return response()->json([
                'response' => $e->getMessage(),
            ], HTTP_STATUS_SERVER_ERROR);
        }
    }

    public function open_document(Request $request)
    {
        try {
            // $user = auth()->user();
            $data = [];
            $data = $this->DocumentService->open_document($request);

            $status = HTTP_STATUS_OK;
            $message = HTTP_SUCCESS;
            if (empty($data)) {
                $status = HTTP_NOT_FOUND;
                $data = 'Unable to open document!';
            }

            return response()->json([
                'status' => $status,
                'response' => $data,
            ], $status);

        } catch (\Exception $e) {
            return response()->json([
                'response' => $e->getMessage(),
            ], HTTP_STATUS_SERVER_ERROR);
        }
    }
}
