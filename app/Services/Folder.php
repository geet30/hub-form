<?php
namespace App\Services;

use App\Models\Admin\Folder as folderModel;

class Folder
{
    /**
     * get folders
     * @return collection
     */

    public function getFolders()
    {
        $folders = folderModel::withCount('documents')->get();
        return $folders;
    }

    /**
     * save
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */

    public function saveFolder($request)
    {

        $folder = folderModel::create([
            'name' => $request->get('folder_name'),
            'futher_sub_folder' => !empty($request->get('sub_folder_name')) ? 1 : 0,
            'master' => 1,
        ]);
        // pr($folder);die;

        if ($folder->futher_sub_folder == 1) {
            $sub_folder = folderModel::create([
                'name' => $request->get('sub_folder_name'),
                'parent_folder_id' => $folder->id,
            ]);
            // $folder->update(['parent_folder_id' => $folder->futher_sub_folder,'sub_folder_name' => $request->get('folder_name'),]);
        }
        return $folder;
    }
}
