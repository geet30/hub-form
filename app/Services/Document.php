<?php
namespace App\Services;

use App\Models\Document as DocumentModel;
use App\Models\UserDocument as UserDocumentModel;

// use Carbon\Carbon;

class Document
{
    /**
     * get LegalReminder
     * @return collection
     */
    public function getListing($name = null, $request, $where = [])
    {
        $user_id = $request['user_id'];
        $listing = DocumentModel::with(['user_doc' => function ($query) use ($user_id) {
            $query->where('user_id', $user_id);
        }])->where('file_name', 'like', '%' . $name . '%');

        if (is_array($where) && !empty($where)) {
            $listing = $listing->where($where);
        }

        $listing = $listing->latest()->paginate(10);
        return $listing;
    }


    public function getListingByRole($name = null, $request, $where = [])
    {
        $user_id = auth()->user()->users_details->i_ref_role_id;
        $listing = DocumentModel::with(['user_doc' => function ($query) use ($user_id) {
            $query->where('i_ref_owner_role_id', $user_id);
        }])->where('file_name', 'like', '%' . $name . '%');

        if (is_array($where) && !empty($where)) {
            $listing = $listing->where($where);
        }

        $listing = $listing->latest()->paginate(10);
        return $listing;
    }

    /**
     * check the document
     * opened by which user
     */
    public function open_document($request)
    {
        if (!empty($request['user_id']) && !empty($request['document_id'])) {
            if (UserDocumentModel::where('user_id', $request['user_id'])
            ->where('document_id', $request['document_id'])->where('is_opened', '=', 1)->count() > 0) {
                return 'Document already opened';
            } else {
                $UserDocument = new UserDocumentModel();
                $UserDocument->user_id = $request['user_id'];
                $UserDocument->document_id = $request['document_id'];
                $UserDocument->is_opened = !empty($request['is_opened']) ? $request['is_opened'] : 0;
                if ($UserDocument->save()) {
                    return 'Document opened successfully';
                }
            }
        }
    }
}
