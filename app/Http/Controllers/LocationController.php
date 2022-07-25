<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Locations;
use App\Models\Country;
use App\Services\P2B as P2BService;
use Auth;
use App\Http\Requests\LocationRequest;

class LocationController extends Controller
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
        $active = "location";
        $location_data = Locations::select('id', 'vc_name','vc_description', 'vc_address', 'i_status')->orderBy('id', 'desc')->get();

        return view('admin.location.index', compact('active', 'location_data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $countries = Country::select('id', 'name')->where('status', 1)->get();
        $active = 'location';
        $status = $this->statusArray;
        return view('admin.location.create', compact('active', 'countries', 'status'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LocationRequest $request)
    {
        try {
            if ($request->validated()) {

                $input = $request->all();
                $input['vc_description']= !empty($request->input('vc_description'))?$request->input('vc_description') :"";
                $company = $this->P2bService->getUser(Auth::id());
                $company_id = isset($company['users_details']['i_ref_company_id'])?$company['users_details']['i_ref_company_id']:'';
                $input['i_ref_company_id'] = $company_id;
                $locationRow = Locations::create($input);
                return redirect()->route('location.index')->with('success', "Location saved Successfully!");
            }
        }catch (Exception $ex) {
            return redirect()->route('location.index')->with('error', $ex->getMessage());
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
        $id = encrypt_decrypt('decrypt', $id);
        $active = 'location';
        $location_data = Locations::with(['company' => function($query){
            $query->select('id', 'vc_company_name', 'vc_logo');
        },
        'country' => function($query){
            $query->select('id', 'name');
        },
        'state' => function($query){
            $query->select('id', 'name');
        },
        'locations_bu' => function($query){
            $query->select('id', 'vc_short_name', 'i_ref_location_id', 'vc_description',  'created', 'modified');
        }
        ])->where('id', $id)->first();
        if(!empty($location_data)){
            return view('admin.location.show', compact('active', 'location_data'));
        }else{
            return redirect()->route('location.index')->with('error', 'Location doesnot exist!');
        }
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
        $countries = Country::select('id', 'name')->where('status', 1)->get();
        $active = 'location';
        $status = $this->statusArray;
        $location_data = Locations::with(['country.states' => function($query){
            $query->select('id', 'name', 'country_id');
        }])->where('id', $id)->first();
        return view('admin.location.edit', compact('active', 'status', 'location_data', 'countries'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(LocationRequest $request, $id)
    {
        try {
            if ($request->validated()) {
                $id = encrypt_decrypt('decrypt', $id);
                $edit = 0;
                $locationRow = Locations::withCount('locations_bu')->findOrFail($id);
                if($request->i_status == 0){
                    if($locationRow->locations_bu_count > 0){
                        return redirect()->back()->with("error", "Unable to inactive Location ! It is mapped to Business Unit.");
                    }else{
                        $edit = 1;
                    }
                }else{
                    $edit = 1;
                }
                
                if($edit == 1){
                    $input = $request->all();
                    $locationRow->update($input);
                    return redirect()->route('location.index')->with('success', "Location updated Successfully!");
                }
            }
        }catch (Exception $ex) {
            return redirect()->route('location.index')->with('error', $ex->getMessage());
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
            $location_data = Locations::withCount('locations_bu')->findOrFail($id);
            if($location_data->locations_bu_count > 0){
                session()->flash('error', "Unable to archive Location ! It is mapped to Business Unit !");
            }else{
                $location_data->delete();
                session()->flash('success', "Location archived successfully !");
            }
            return;            
        } catch (Exception $ex) {
            session()->flash('error', $ex->getMessage());
            return;
        }
    }

    /**
     * Display a listing of the archived resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function archived(Request $request)
    {

        $active = "location";
        $location_data = Locations::onlyTrashed()->select('id', 'vc_name','vc_description', 'vc_address', 'i_status')->orderBy('id', 'desc')->get();

        return view('admin.location.archived', compact('active', 'location_data'));

    }

    /**
     * Restore archived Business Units.
     *
     * @return \Illuminate\Http\Response
     */
    public function restore(Request $request)
    {  
        $id = encrypt_decrypt('decrypt', $request->id);
        try {
            $data = Locations::withTrashed()->find($id);
            $data->restore();
            $request->session()->flash('success', 'Location restored successfully!');
            return;
        }catch (Exception $ex) {
            session()->flash('error', $ex->getMessage());
            return;
        }
    }
}
