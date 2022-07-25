<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\Level;
use App\Http\Requests\LevelRequest;
use App\Services\P2B as P2BService;

class LevelController extends Controller
{

    public function __construct()
    {
        $this->P2bService = new P2BService();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $active = 'level';
        $level_data = Level::select('id', 'vc_name', 'i_start_limit', 'i_end_limit', 'i_status')
            ->withCount('level_role')
            ->orderBy('i_end_limit', 'desc')->get();
        return view('admin.level.index', compact('active', 'level_data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $active = 'level';
        $status = $this->statusArray;
        return view('admin.level.create', compact('active', 'status'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LevelRequest $request)
    {
        try {
            if ($request->validated()) {

                $input = $request->all();
                $company = $this->P2bService->getUser(Auth::id());
                $company_id = isset($company['users_details']['i_ref_company_id'])?$company['users_details']['i_ref_company_id']:'';
                $input['i_ref_company_id'] = $company_id;
                $levelRow = Level::create($input);
                return redirect()->route('level.index')->with('success', "Level saved Successfully!");
            }
        }catch (Exception $ex) {
            return redirect()->route('level.index')->with('error', $ex->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $id = encrypt_decrypt('decrypt', $id);
        $active = 'level';
        $status = $this->statusArray;
        $level_data = Level::where('id', $id)->first();
        return view('admin.level.edit', compact('active', 'status', 'level_data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(LevelRequest $request, $id)
    {
        try {
            if ($request->validated()) {
                $id = encrypt_decrypt('decrypt', $id);
                $levelRow = Level::withCount('level_role')->findOrFail($id);
                $edit = 0;
                if($request->i_status == 0){
                    if($levelRow->level_role_count > 0){
                        return redirect()->back()->with("error", "Unable to inactive Level! It is mapped to Roles .");
                    }else{
                        $edit = 1;
                    }
                }else{
                    $edit = 1;
                }
                
                if($edit == 1){
                    $input = $request->all();
                    $levelRow->update($input);
                    return redirect()->route('level.index')->with('success', "Level updated Successfully!");
                }
            }
        }catch (Exception $ex) {
            return redirect()->route('level.index')->with('error', $ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $id = encrypt_decrypt('decrypt', $id);
        try {
            $level_data = Level::withCount('level_role')->findOrFail($id);
            if($level_data->level_role_count > 0){
                session()->flash('error', "Unable to archive Level ! It is mapped to Roles !");
            }else{
                $level_data->delete();
                session()->flash('success', "Level archived successfully !");
            }
            return;            
        } catch (Exception $ex) {
            session()->flash('error', $ex->getMessage());
            return;
        }
    }
    
}
