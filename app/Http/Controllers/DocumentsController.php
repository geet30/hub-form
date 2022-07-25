<?php

namespace App\Http\Controllers;
use App\Models\Admin\Folder;
use App\Models\Business_unit;
use App\Models\Category;
use App\Models\Document;
use App\Models\UserDocument;
use App\Models\Users;
use App\Models\Country;
use App\Services\P2B as P2BService;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


class DocumentsController extends Controller
{
    public $ids=[];
    public $parent_data=[];
    public function __construct()
    {
        $this->P2bService = new P2BService();
        
    }

    /**
     * Listing of all the
     * documents with related data
     */
    public function index($value = '')
    {

      
        $doc_listing = Document::with([
            // 'owner' => function ($query) {
            //     $query->select('id', 'vc_fname', 'vc_mname', 'vc_lname');
            // },
            // 'owner.users_details.roles',
            'business_unit' => function ($query) {
                $query->select('id', 'vc_short_name');
            },
            'department' => function ($query) {
                $query->select('id', 'vc_name');
            },
            'project' => function ($query) {
                $query->select('id', 'vc_name');
            },
            'category' => function ($query) {
                $query->select('id', 'name');
            },
            'folder' => function ($query) {
                $query->select('id', 'name');
            },
        ])->latest();

        if (auth()->check() && auth()->user()->user_type == 'employee') {
            $doc_listing = $doc_listing->whereUseInMobile(true)
            // ->orWhere('owner_id', Auth::id());
            ->orWhere('i_ref_owner_role_id', auth()->user()->users_details->i_ref_role_id);   
        }elseif(auth()->check() && auth()->user()->user_type == 'supplier'){
            $doc_listing =$doc_listing->Where('owner_id', Auth::id());
        }


        $doc_listing = $doc_listing->select('id', 'title', 'folder_id', 'owner_id', 'file_name', 'file_type', 'category_id', 'business_unit_id', 'department_id', 'project_id', 'share_with_supplier', 'Use_in_mobile', 'description', 'expires_at'
        ,'i_ref_owner_role_id','company_id'
        )->get();

        // dd($doc_listing );
        $active = "documents";
        $departments = $this->P2bService->getDepartments([], ['id', 'vc_name']);
        $business_unit = $this->P2bService->getBusinessUnits();
        $categories = Category::select('id', 'name')->get();
        // $folders = Folder::select('id', 'name','parent_folder_id')->get();
        $all_folders = Folder::select('id', 'name',"parent_folder_id")->get()->toArray();
        $newArray =[];
        foreach ($doc_listing as $document) {
        
            if(isset($document->folder->id))
            $newArray[] = $this->ParentFolder($all_folders,$document->folder->id);
        }
        if(isset($newArray) && !empty($newArray)){
            foreach($newArray as $row =>$value){
                $this->parent_data=[];
                $this->getParentfolder($value);            
                $ids = array_unique($this->ids);    
                $doc_listing[$row]['parent_data'] = $this->parent_data;
            }
        }

        $this->ids=[];
        $this->parent_data=[];
        $newArray=[];
        foreach ($all_folders as $key => $element) {
            $newArray[] = $this->ParentFolder($all_folders,$element['id']);
        }
        $parent_data = [];
        foreach($newArray as $row){
            $this->parent_data=[];
            $this->getParentfolder($row);            
            $ids = array_unique($this->ids);    
            $parent_data[] = $this->parent_data;
        }
        
                              
        $users = Users::select('id', 'vc_fname', 'vc_lname')->get();
        // dd($doc_listing);
        return view('admin.document.index', compact('doc_listing', 'active', 'departments', 'categories', 'business_unit', 'all_folders', 'users','parent_data'));
    }


    public function ParentFolder(array $elements,$parentId)
    {
        $branch = [];
        foreach ($elements as $key => $element) {
            if ($element['id'] == $parentId) {
                $children = $this->ParentFolder($elements,$element['parent_folder_id']);
                // dd($children);
                if ($children) {
                    $element['parent_folder'] = $children;
                }
                array_push($branch, $element);
            }
        }
        return $branch;
    }


    public function getParentfolder(array $rows)
    {
        foreach ($rows as $row) {
            if (isset($row['parent_folder'])) {
                array_push($this->ids, $row['id']);
                $data = array(
                    'parent_folder_id' => $row['parent_folder_id'],
                    'name' => $row['name']
                );
                array_push($this->parent_data, $data);
                $this->getParentfolder($row['parent_folder']);
            } else {
                array_push($this->ids, $row['id']);
                $data = array(
                    'parent_folder_id' => $row['parent_folder_id'],
                    'name' => $row['name']
                );
                array_push($this->parent_data, $data);
            }
        }
        return $this->ids;
    }


    /**
     * Check Document exist or not
     */
    public function checkDocument(Request $request)
    {
   
            // dd(Document::whereTitle($request->name)->exists());
        if ($request->has('name') && !empty($request->name)) {
            if (Document::whereTitle($request->name)->exists()) {
                if($request->has('id') && !empty($request->id)){
                    if (Document::whereTitle($request->name)->where("id",'!=',$request->id)->exists()) {
                        echo "false";                        
                    }else{
                        echo 'true';
                    }       
                }else{
                    echo "false";
                }
            } else {
                echo "true";
            }
        } else {
            echo "true";
        }
        die;
    }

    /**
     * create document
     */
    public function create_document(Request $request)
    {
        $rule = [
            'name' => 'required',
            // 'expiry_date' => 'required',
            'category' => 'required',
        ];
        $message = [
            'name.required' => 'Please enter the Title.',
            // 'expiry_date.required' => 'Please enter the Title.',
            'category.required' => 'Please enter the Title.',
        ];
        try {
            if ($this->validate($request, $rule, $message)) {
                // print_r($request->file('file'));die;
                $file = $request->file('file');

                //upload file in uploads folder
                if (isset($file) && $file !== "" || !empty($request['url'])) {
                    if (isset($file) && $file !== "" && !empty($file)) {
                        $fileName = time() . '.' . $file->extension();
                        $file->move(public_path('documentLibrary'), $fileName);
                        $file_type = $file->getClientMimeType();


                        if (!empty($file_type)) {
                            $type = explode("/", $file_type);
                            if ($type[0] == 'image') {
                                $document_type = 1;
                            } elseif ($type[0] == 'audio') {
                                $document_type = 2;
                            } elseif ($type[0] == 'application' && $type[1] == 'pdf') {
                                $document_type = 3;
                            } elseif ($type[0] == 'application' && $type[1] != 'pdf') {
                                $document_type = 5;
                            } elseif ($type[0] == 'video') {
                                $document_type = 4;
                            } else {
                                $document_type = 0;
                            }
                        }
                    } elseif (!empty($request['url'])) {
                        $fileName = $request['url'];
                        $document_type = 6;
                    }
                    $Document = new Document;
                    $Document->title = $request['name'];
                    $Document->owner_id = $request['owner'];
                    $Document->category_id = $request['category'];
                    $Document->user_id = Auth::id();
                    $Document->company_id = $request['company'];
                    $Document->business_unit_id = $request['business_unit'];
                    $Document->department_id = $request['department'];
                    $Document->project_id = $request['project'];
                    $Document->location_id = $request['location'];
                    $Document->description = $request['description'];
                    $Document->expires_at = !empty($request['expiry_date']) ? date("Y-m-d", strtotime($request['expiry_date'])) : null;
                    $Document->folder_id = $request['folder'];
                    $Document->file_name = $fileName;
                    $Document->file_type = $document_type;

                    if ($Document->save()) {
                        $request->session()->flash('success', 'Document uploaded successfully!');
                    } else {
                        $request->session()->flash('error', 'Failed to upload Document!');
                    }
                } else {
                    $request->session()->flash('error', 'Please select document file to upload');
                }
            }
        } catch (\Exception $e) {
            return response()->json([
                'data' => $e->getMessage(),
                'message' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * edit document
     * @return document data
     *
     */
    public function edit_document(Request $request)
    {
        $document = Document::with('template', 'business_unit', 'department', 'project', 'category', 'folder')->where('id', $request['id'])->first();
        $document->owner=CheckUserType($document->i_ref_owner_role_id, $document->owner_id);
        // dd($owner);
        
        return $document;
    }

    /**
     * update document
     * data
     */
    public function update_document(Request $request)
    {
        // dd($request->all()); 
        // dd()
        if ($request) {
            if (!empty($request['id'])) {
                $document = Document::find($request['id']);
                $document->description = $request['description'];
                $document->expires_at = !empty($request['expiry_date']) ? date("Y-m-d", strtotime($request['expiry_date'])) : null;
                $document->folder_id = $request['folder'];
                $document->title = $request['name'];
                $document->owner_id = $request['owner'];
                $document->category_id = $request['category'];

                $document->business_unit_id = $request['business_unit'];
                $document->department_id = $request['department'];
                $document->project_id = $request['project'];


                $file = $request->file('file');

                if (isset($file) && $file !== "" && !empty($file)) {
                    $fileName = time() . '.' . $file->extension();
                    $file->move(public_path('documentLibrary'), $fileName);
                    $file_type = $file->getClientMimeType();

                    if (!empty($file_type)) {
                        $type = explode("/", $file_type);
                        if ($type[0] == 'image') {
                            $document_type = 1;
                        } elseif ($type[0] == 'audio') {
                            $document_type = 2;
                        } elseif ($type[0] == 'application' && $type[1] == 'pdf') {
                            $document_type = 3;
                        } elseif ($type[0] == 'application' && $type[1] != 'pdf') {
                            $document_type = 5;
                        } elseif ($type[0] == 'video') {
                            $document_type = 4;
                        } else {
                            $document_type = 0;
                        }
                    }
                    $document->file_name = $fileName;
                    $document->file_type = $document_type;
                } elseif (!empty($request['url'])) {
                    $fileName = $request['url'];
                    $document_type = 6;
                    $document->file_name = $fileName;
                    $document->file_type = $document_type;
                }

                // dd($document);
                if ($document->update()) {
                    return redirect()->route('documents')
                        ->with('success', 'Document updated successfully!');
                } else {
                    return redirect()->route('documents')
                        ->with('error', 'Failed to update Document!');
                }
            } else {
                return redirect()->route('documents')
                    ->with('error', 'Failed to update Document!');
            }
        } else {
            return redirect()->route('documents')
                ->with('error', 'Failed to update Document!');
        }
    }
    function validateCompanyName(Request $request) {
        if ($request->has('name') && !empty($request->name)) {
            if (Category::where('name',$request->name)->where('company_id',Auth::user()->users_details->i_ref_company_id)->exists()) {
                if($request->has('id') && !empty($request->id)){
                    if (Category::where('name',$request->name)->where('company_id',Auth::user()->users_details->i_ref_company_id)->where("id",'!=',$request->id)->exists()) {
                        echo "false";                        
                    }else{
                        echo 'true';
                    }       
                }else{
                    echo "false";
                }
            } else {
                echo "true";
            }
        } else {
            echo "true";
        }
        die;
        // if ($request->has('name') && !empty($request->name)) {
      
        //     if (Category::where('name',$request->name)->where('company_id',Auth::user()->users_details->i_ref_company_id)->exists()) {
        //          echo "false";                        
        //     }else{
        //          echo 'true';
        //     }       
                
        // } else {
        //     echo "true";
        // }
        
        // die;
    }
    /**
     * create new category
     */
  
   
    public function create_category(Request $request)
    {
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'name' => ['required',
                        Rule::unique('categories')->where(function($query) {
                        $query->where('company_id', '!=', Auth::user()->users_details->i_ref_company_id);
                         
                    })
                ]  

                ]
            );

            if ($validator->fails()) {
                $errors = $validator->errors();
                $errorsBag = [];
                foreach ($errors->messages() as $key => $value) {
                    $ret = [
                        'element' => $key,
                        'message' => $value[0],
                        'obj' => [$key => $value[0]],
                    ];
                    array_push($errorsBag, $ret);
                }
                return $this->returnResponse(Response::HTTP_UNPROCESSABLE_ENTITY, false, "validation_error", [], $errorsBag);
            }

            $input = $request->only(['name']);
            Category::create($input);
            \Session::flash('success', trans("response.caregory_created"));
            return $this->returnResponse(Response::HTTP_CREATED, true, trans("response.caregory_created"));
        } catch (Exception $ex) {
            return $this->returnResponse(Response::HTTP_INTERNAL_SERVER_ERROR, false, $ex->getMessage());
        }
    }
    
    public function edit_category(Request $request)
    {
        try {

            $validator = Validator::make(
                $request->all(),
                [
                    'name' =>  Rule::unique('categories')->ignore($request->category_id),

                ]
            );


            if ($validator->fails()) {
                $errors = $validator->errors();
                $errorsBag = [];
                foreach ($errors->messages() as $key => $value) {
                    $ret = [
                        'element' => $key,
                        'message' => $value[0],
                        'obj' => [$key => $value[0]],
                    ];
                    array_push($errorsBag, $ret);
                }
                return $this->returnResponse(Response::HTTP_UNPROCESSABLE_ENTITY, false, "validation_error", [], $errorsBag);
            }
            // dd($request->all());
            $cat = Category::find($request->category_id);
            $cat->name = $request->name;
            $cat->save();
            \Session::flash('success', trans("response.caregory_created"));
            return $this->returnResponse(Response::HTTP_CREATED, true, trans("response.caregory_created"));
        } catch (Exception $ex) {
            return $this->returnResponse(Response::HTTP_INTERNAL_SERVER_ERROR, false, $ex->getMessage());
        }
    }
    /**
     * Check category is not exists
     */
    public function checkCategory(Request $request)
    {
        if ($request->has('name') && !empty($request->name)) {
            if (Category::whereName($request->name)->exists()) {
                echo "false";
            } else {
                echo "true";
            }
        } else {
            echo "true";
        }
        die;
    }
    /**
     * archive document
     */
    public function archive_document(Request $request)
    {
        if ($request->id) {
            $data = Document::find($request->id);

            if ($data->delete()) {
                $request->session()->flash('success', 'Document archived successfully!');
            } else {
                $request->session()->flash('error', 'Failed to archive Document!');
            }
        } else {
            $request->session()->flash('error', 'Failed to archive Document!');
        }
    }

    /**
     * get listing of
     * archived document and
     * show in table
     */
    public function getArchiveListing()
    {
        $doc_listing = Document::onlyTrashed()->with([
            // 'owner' => function ($query) {
            //     $query->select('id', 'vc_fname', 'vc_mname', 'vc_lname');
            // },
            'business_unit' => function ($query) {
                $query->select('id', 'vc_short_name');
            },
            'department' => function ($query) {
                $query->select('id', 'vc_name');
            },
            'project' => function ($query) {
                $query->select('id', 'vc_name');
            },
            'category' => function ($query) {
                $query->select('id', 'name');
            },
            'folder' => function ($query) {
                $query->select('id', 'name');
            },
        ]);

        if (auth()->check() && auth()->user()->user_type == 'supplier') {
            $userId = Auth::id();
            $doc_listing = $doc_listing->whereRaw("(user_id = $userId)")
            ->orderBy('id', 'desc');
        }elseif (auth()->check() && auth()->user()->user_type == 'employee')  {
            $doc_listing = $doc_listing->whereUseInMobile(true)
            ->orWhere('i_ref_owner_role_id', auth()->user()->users_details->i_ref_role_id);   
        }
        $doc_listing = $doc_listing->orderBy('id', 'desc');
        $doc_listing = $doc_listing->get();

        $active = "archive_doc";
        $departments = $this->P2bService->getDepartments([], ['id', 'vc_name']);
        $categories = Category::select('id', 'name')->get();
        $folders = Folder::select('id', 'name')->get();

        return view('admin.document.deleted-document', compact('doc_listing', 'active', 'departments', 'categories', 'folders'));
    }

    /**
     * restore document
     * which is archived
     */

    public function restore_document(Request $request)
    {
        if ($request->id) {
            $data = Document::withTrashed()->find($request->id);
            if ($data->restore()) {
                $request->session()->flash('success', 'Document restored successfully!');
            } else {
                $request->session()->flash('error', 'Failed to restore Document!');
            }
        } else {
            $request->session()->flash('error', 'Failed to restore Document!');
        }
    }

    /**
     * get activity log
     * of documents
     */
    public function activity_log(Request $request)
    {
        $opened_doc = UserDocument::where('document_id', $request['id'])->where('is_opened', 1)->with('user')->get();
        $doc_list = Document::where('id', $request['id'])->with('template', 'business_unit', 'department', 'project', 'category')->get();
        $doc_list->owner=CheckUserType($doc_list->i_ref_owner_role_id, $doc_list->owner_id);
        return response()->json([
            'open_doc' => $opened_doc,
            'doc_list' => $doc_list,
        ]);
    }

    /**
     * Show Documents to supplier
     */
    public function supplierDocuments()
    {
        $active = "documents";

        $doc_listing = Document::with([
            'owner',
            'business_unit' => function ($query) {
                $query->select('id', 'vc_short_name');
            },
            'department' => function ($query) {
                $query->select('id', 'vc_name');
            },
            'project' => function ($query) {
                $query->select('id', 'vc_name');
            },
            'category' => function ($query) {
                $query->select('id', 'name');
            },
            'folder' => function ($query) {
                $query->select('id', 'name');
            },
        ])->whereShareWithSupplier(true)->latest()->get();
        $departments = $this->P2bService->getDepartments();
        // $business_unit = $this->P2bService->getBusinessUnits();
        $categories = Category::select('id', 'name')->get();
        return view('admin.document.supplier-documents', compact('active', 'categories', 'departments', 'doc_listing'));
    }

    /**
     * Update Document
     */
    public function update(Request $request, $id)
    {
        try {
            $input = $request->all();
            $row = Document::findOrFail($id);
            $row->fill($input);
            $row->save();
            return $this->returnResponse(Response::HTTP_OK, true, trans("response.document_updated"));
        } catch (\Illuminate\Database\QueryException $ex) {
            return $this->returnResponse(Response::HTTP_INTERNAL_SERVER_ERROR, false, $ex->getMessage());
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $ex) {
            return $this->returnResponse(Response::HTTP_INTERNAL_SERVER_ERROR, false, $ex->getMessage());
        }
    }

    /**
     * get departments and
     * projects according to selected
     * value of business unit
     */

    public function BU_data(Request $request)
    {
        // die('here');
        if (!empty($request['id'])) {
            $Business_unit = Business_unit::with([
                'business_dept.dept_data' => function ($query) {
                    $query->where('i_status', 1);
                },
                'projects' => function ($query) {
                    $query->where('i_status', 1);
                },
                'userDetail.user' => function ($query) {
                    $query->select('id', 'vc_fname', 'vc_mname', 'vc_lname')->where('i_status', 1);
                },
                'roles' => function ($query) {
                    $query->where('i_status', 1);
                },
                'roles.user_detail.user'
            ])->where('id', $request['id'])->first();
            // pr($Business_unit);die;
            return $Business_unit;
        }
    }

    /**
     * get states
     * according to selected
     * value of country
     */

    public function country_data(Request $request)
    {
        if (!empty($request['id'])) {
            $country = Country::with('states')->where('id', $request['id'])->first();
            return $country;
        }
    }




    function delete_category(Request $request)
    {
        if ($request->id) {
            $id=$request->id;
            $data= Category::with(['document'])->where('id', $id)->get();
            if(!empty($data[0]->document)){
                return json_encode(["status"=>"failed"]);
                die;
            }
            $data = Category::find($request->id);
            if ($data->delete()) {
                $request->session()->flash('success', 'Category Delete successfully!');
            } else {
                $request->session()->flash('error', 'Failed to Delete Category!');
            }
        } else {
            $request->session()->flash('error', 'Failed to Delete Category!');
        }
    }

    function view_document(Request $request)
    {

        // dd($request->all());
        $input['user_id'] = Auth::id();
        $input['document_id'] = $request['document_id'];
        $input['is_opened'] = 1;
        $opened_doc = UserDocument::create($input);

        $request->session()->flash('success', 'log successfully!');
    }

    public function download_document(Request $request,$id){
        
        $file_name = Document::find($id)->file_name;
        if($file_name){
            $file_path = public_path('documentLibrary/'.$file_name);
            return response()->download( $file_path);
        }
        
    }
    
}
