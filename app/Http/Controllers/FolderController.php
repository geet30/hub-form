<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Document;
use App\Models\Admin\Folder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\SaveFolderRequest;
use App\Services\Folder as FolderService;
use Symfony\Component\HttpFoundation\Response;
use App\Services\P2B as P2BService;
class FolderController extends Controller
{

    public $ids = [];
    public $parent_data = [];

    public function __construct()
    {
        $this->folder = new FolderService();
        $this->P2bService = new P2BService();
    }
    //get listing of all documents
    public function index($id = null)
    {
        $id = encrypt_decrypt('decrypt', $id);
        $active = "manage_folder";
        $parent_id = !empty($id) ? $id : 0;
        $folders = Folder::where('parent_folder_id', $parent_id)->with('sub_folders')->get();
        $all_folders = Folder::select('id', 'name', 'parent_folder_id')->get()->toArray();
        $newArray = $this->countParentFolder($all_folders, $parent_id);
        // dd($all_folders);
        array_push($this->ids, (int) $parent_id);
        $this->getParentfolder($newArray);
        $ids = array_unique($this->ids);
        $parent_data = $this->parent_data;
        $total_child =  count($ids);
        $documents = Document::select('id', 'title', 'file_name', 'file_type', 'folder_id')->where('folder_id', $parent_id)->get();
       
        $this->ids=[];
        $this->parent_data=[];
        $newArray=[];
        foreach ($all_folders as $key => $element) {
            $newArray[] = $this->countParentFolder($all_folders,$element['id']);
        }
        $select2folder = [];
        foreach($newArray as $row){
            $this->parent_data=[];
            $this->getParentfolder($row);            
            $ids = array_unique($this->ids);    
            $select2folder[] = $this->parent_data;
        }

        // dd($select2folder);
        $business_unit = $this->P2bService->getBusinessUnits();
        $categories = Category::select('id', 'name')->get();

        return view('admin.folder.index', compact('folders', 'documents', 'active', 'parent_id', 'total_child', 'parent_data','business_unit','categories','id','select2folder'));
    }

    //save folder
    public function save(SaveFolderRequest $request)
    {

        try {
            $folder = $request->all();
            $folderRow = Folder::create($folder);
            $folders = Folder::where('parent_folder_id', $folderRow->parent_folder_id)->get();
            $documents = Document::select('id', 'title', 'file_name', 'file_type', 'folder_id')->where('folder_id', $folderRow->parent_folder_id)->get();
            $data['folderStructure'] = view('components.folder-structure', compact('folders', 'documents'))->render();
            return $this->returnResponse(Response::HTTP_OK, true, "Folder created successfully.", $data);
        } catch (\Exception $ex) {
            return $this->returnResponse(Response::HTTP_INTERNAL_SERVER_ERROR, false, $ex->getMessage());
        }

    }


    /**
     * count parent folder
     * of folder
     */
    public function countParentFolder(array $elements, $parentId = 0)
    {
        $branch = [];
        foreach ($elements as $key => $element) {
            if ($element['id'] == $parentId) {
                $children = $this->countParentFolder($elements, $element['parent_folder_id']);
                if ($children) {
                    $element['parent_folder'] = $children;
                }
                array_push($branch, $element);
            }
        }
        // die;
        return $branch;
    }

    /**
     * Check folder
     */
    public function checkFolderName(Request $request)
    {
        if ($request->has('name') && !empty($request->name)) {
            if (Folder::whereName($request->name)->exists()) {
                echo "false";
            } else {
                echo "true";
            }
        } else {
            echo "true";
        }
        die;
    }
    /******
     * rename name
     * of folder
     */
    public function rename_folder(Request $request, $id)
    {
        try {
            $folder = Folder::findOrFail($id);
            $folder->fill($request->all());
            $folder->save();
            $folders = Folder::where('parent_folder_id', $folder->parent_folder_id)->get();
            $documents = Document::select('id', 'title', 'file_name', 'file_type', 'folder_id')->where('folder_id', $folder->parent_folder_id)->get();
            $data['folderStructure'] = view('components.folder-structure', compact('folders', 'documents'))->render();
            return $this->returnResponse(Response::HTTP_OK, true, "Folder rename successfully.", $data);
        } catch (\Exception $ex) {
            return $this->returnResponse(Response::HTTP_INTERNAL_SERVER_ERROR, false, $ex->getMessage());
        }
    }

    /**
     * Delete Folder
     * or sub folder
     */
    public function delete_folder($id)
    {
        try {
            $folders = Folder::select('id', 'name', 'parent_folder_id')->get()->makeHidden(['encrypted_id'])->toArray();
            $newArray = $this->buildTree($folders, $id);
            array_push($this->ids, (int) $id);
            $this->getSubfolder($newArray);
            $ids = array_unique($this->ids);
            $documents = Document::whereIn('folder_id', $ids)->exists();
            if($documents){
                return $this->returnResponse(Response::HTTP_INTERNAL_SERVER_ERROR, false, 'Unable to delete Folder. Document exist in Folder!');
            }
            Folder::whereIn('id', $ids)->delete();

            return $this->returnResponse(HTTP_STATUS_OK, true, 'Folder deleted successfully.');

        } catch (\Exception $ex) {
            return $this->returnResponse(Response::HTTP_INTERNAL_SERVER_ERROR, false, $ex->getMessage());
        }
    }

    /**
     * build tree array
     */
    public function buildTree(array $elements, $parentId = 0)
    {
        $branch = [];
        foreach ($elements as $key => $element) {
            if ($element['parent_folder_id'] == $parentId) {
                $children = $this->buildTree($elements, $element['id']);
                if ($children) {
                    $element['sub_folder'] = $children;
                }
                array_push($branch, $element);
            }
        }
        return $branch;
    }

    
    /**
     * Get All sub folder id's
     */
    public function getSubfolder(array $rows)
    {
        foreach ($rows as $row) {
            if (isset($row['sub_folder'])) {
                array_push($this->ids, $row['id']);
                $this->getSubfolder($row['sub_folder']);
            } else {
                array_push($this->ids, $row['id']);
            }
        }
        return $this->ids;
    }

    /**
     * Get All parent folder id's
     */
    public function getParentfolder(array $rows)
    {
        foreach ($rows as $row) {
            if (isset($row['parent_folder'])) {
                array_push($this->ids, $row['id']);
                $data = array(
                    'parent_folder_id' => $row['parent_folder_id'],
                    'name' => $row['name'],
                    'encrypted_id' => $row['encrypted_id']
                );
                array_push($this->parent_data, $data);
                $this->getParentfolder($row['parent_folder']);
            } else {
                array_push($this->ids, $row['id']);
                $data = array(
                    'parent_folder_id' => $row['parent_folder_id'],
                    'name' => $row['name'],
                    'encrypted_id' => $row['encrypted_id']
                );
                array_push($this->parent_data, $data);
            }
        }
        return $this->ids;
    }

    public function update(Request $request, $id)
    {
        try {
            $input = $request->only(['parent_folder_id']);
            $row = Folder::findOrFail($id);
            $row->fill($input);
            $row->save();
            return $this->returnResponse(HTTP_STATUS_OK, true, trans('response.folder_updated_successfully'));
        } catch (\Illuminate\Database\QueryException $ex) {
            return $this->returnResponse(HTTP_STATUS_SERVER_ERROR, false, $ex->getMessage());
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $ex) {
            return $this->returnResponse(HTTP_STATUS_SERVER_ERROR, false, $ex->getMessage());
        }
    }


    //get listing of all archived folders
    public function archive_folders($id = null)
    {   
        $id = encrypt_decrypt('decrypt', $id);
        $active = "archive_folders";
        $parent_id = !empty($id) ? $id : 0;
        $folders = Folder::onlyTrashed()->where('parent_folder_id', $parent_id)->with('sub_folders')->get();
        $all_folders = Folder::select('id', 'name', 'parent_folder_id')->onlyTrashed()->get()->toArray();
        $newArray = $this->countParentFolder($all_folders, $parent_id);
        array_push($this->ids, (int) $parent_id);
        $this->getParentfolder($newArray);
        $ids = array_unique($this->ids);
        $parent_data = $this->parent_data;
        $total_child =  count($ids);
        // dd($all_folders);
        $documents = Document::select('id', 'title', 'file_name', 'file_type', 'folder_id')->where('folder_id', $parent_id)->get();
    //   dd($folders);
        return view('admin.folder.archived', compact('folders', 'documents', 'active', 'parent_id', 'total_child', 'parent_data'));
    }


    public function restore_folder(Request $request)
    {
        if ($request->id) {
            $data = Folder::withTrashed()->find($request->id);
            if ($data->restore()) {

                return $this->returnResponse(HTTP_STATUS_OK, true, 'Folder restored successfully.');
            } else {
                return $this->returnResponse(Response::HTTP_INTERNAL_SERVER_ERROR, false, 'Failed to restore Folder!');            }
        } else {
            $request->session()->flash('error', 'Failed to restore Folder!');
            return $this->returnResponse(Response::HTTP_INTERNAL_SERVER_ERROR, false, 'Failed to restore Folder!');
          
        }
    }
}
