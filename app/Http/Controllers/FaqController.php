<?php

namespace App\Http\Controllers;

use App;
use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\Setting;
use App\Models\EmailSetting;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function __construct()
    {
        // $this->middleware('trimdata');
        // $this->middleware('admin');
        // if (isset($_COOKIE['client_timezone'])) {
        //     date_default_timezone_set($_COOKIE['client_timezone']);
        // }
    }

    /*===========================================
    Display a listing of skills.
    =============================================
     */
    public function index()
    {
        $faqs = Faq::orderBy('id', 'desc')->get();
        $active = "faq";
        return view('admin.faq.index', compact('faqs', 'active'));
    }

    /*================================================================
    Store Skill in database table
     * @param  \App\Http\Requests\FAQRequest  $request
    =================================================================
     */
    public function add_faq(Request $request)
    {

        $rule = [
            'faqs' => 'required',
            'answer' => 'required',
            'status' => 'required',
        ];
        $message = [
            'faqs.required' => 'Please enter the Question.',
            'answer.required' => 'Please enter the Answer.',
            'answer.required' => 'Please select the Status.',
        ];

        if ($this->validate($request, $rule, $message)) {
            $Faq = new Faq();
            $Faq->faqs = $request['faqs'];
            $Faq->answer = $request['answer'];
            $Faq->status = !empty($request['status']) ? $request['status'] : 0;
            if ($Faq->save()) {
                return redirect()->route('faq')
                    ->with('success', 'Faq added successfully!');
            } else {
                return redirect()->route('faq')
                    ->with('error', 'Failed to add Faq!');
            }
        }
    }

    /*======================================================
     * Show the form for editing the specified faq.
     * @param  int  $id
     * @return \Illuminate\Http\Response
    =======================================================
     */
    public function edit_faq(Request $request)
    {
        // die('here');
        // print_r($_POST);die;
        $faq_data = Faq::where('id', $request['id'])->first();
        return $faq_data;
    }

    /*================================================
     * Update the specified skill in storage.
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
    =================================================
     */
    public function update_faq(Request $request)
    {
        if (!empty($request['faq_id'])) {
            $input = $request->only(['faqs', 'answer', 'status']);
            $Faq = Faq::find($request['faq_id']);
            $Faq->fill($input);

            if ($Faq->save()) {
                return redirect()->route('faq')
                    ->with('success', 'Faq updated successfully!');
            } else {
                return redirect()->route('faq')
                    ->with('error', 'Failed to update Faq!');
            }
        } else {
            return redirect()->route('faq')
                ->with('error', 'Failed to update Faq!');
        }
    }

    /*================================================
     * delete the specified skill in storage.
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
    =================================================
     */
    public function delete_faq(Request $request)
    {
        if ($request->id) {
            $data = Faq::find($request->id);

            if ($data->delete()) {
                $request->session()->flash('success', 'FAQ deleted successfully!');
            } else {
                $request->session()->flash('error', 'Failed to delete FAQ!');
            }
        } else {
            $request->session()->flash('error', 'Failed to delete FAQ!');

        }
    }

    /*===============================
     *View all Skill Detail
    ======================================
     */
    public function view_faq()
    {
        $faqs = Faq::where('status', 1)->orderBy('id', 'desc')->get();
        return view('admin.faq.faq', compact('faqs'));
    }

    /*===============================
    Terms and coditions page
    =================================
     */
    public function terms_condition()
    {
        $terms_conditions = Setting::where('type', 1)->first();
        $active = 'term_cond';
        return view('admin.faq.term_condition', compact('terms_conditions', 'active'));
    }

    /*===============================
    Store Terms and coditions page
    =================================
     */

    public function add_term_condition(Request $request)
    {

        $file = $request->file('term_condition');
        //upload file in uploads folder
        if (isset($file) && $file !== "") {
            $fileName = 'terms_condition_' . time() . '.' . $file->extension();
            if ($file->move(public_path('uploads'), $fileName)) {
                if (empty($request['id'])) {
                    $Setting = new Setting();
                    $Setting->name = 'Terms and Conditions';
                    $Setting->file = $fileName;
                    $Setting->type = 1;
                    if ($Setting->save()) {
                        return redirect()->route('terms_condition')
                            ->with('success', 'Terms and conditions uploaded successfully!');
                    } else {
                        return redirect()->route('terms_condition')
                            ->with('error', 'Failed to upload terms and conditions!');
                    }
                } elseif (!empty($request['id'])) {
                    $Setting = Setting::find($request['id']);
                    $Setting->name = 'Terms and Conditions';
                    $Setting->file = $fileName;
                    $Setting->type = 1;
                    if ($Setting->update()) {
                        return redirect()->route('terms_condition')
                            ->with('success', 'Terms and conditions uploaded successfully!');
                    } else {
                        return redirect()->route('terms_condition')
                            ->with('error', 'Failed to upload terms and conditions!');
                    }
                }
            } else {
                return redirect()->route('terms_condition')
                    ->with('error', 'Failed to upload terms and conditions!');
            }
        } else {
            return redirect()->route('terms_condition')
                ->with('error', 'Please upload file!');
        }
    }

    /*===============================
    privacy policy page
    =================================
     */
    public function privacy_policy()
    {
        $privacy_policy = Setting::where('type', 2)->first();
        $active = 'privacy_policy';
        return view('admin.faq.privacy_policy', compact('privacy_policy', 'active'));
    }

    /*===============================
    Store privacy policy page
    =================================
     */

    public function add_privacy_policy(Request $request)
    {

        $file = $request->file('privacy_policy');
        //upload file in uploads folder
        if (isset($file) && $file !== "") {
            $fileName = 'privacy_policy_' . time() . '.' . $file->extension();
            if ($file->move(public_path('uploads'), $fileName)) {
                if (empty($request['id'])) {
                    $Setting = new Setting();
                    $Setting->name = 'Privacy Policy';
                    $Setting->file = $fileName;
                    $Setting->type = 2;
                    if ($Setting->save()) {
                        return redirect()->route('privacy_policy')
                            ->with('success', 'Privacy Policy uploaded successfully!');
                    } else {
                        return redirect()->route('privacy_policy')
                            ->with('error', 'Failed to upload Privacy Policy!');
                    }
                } elseif (!empty($request['id'])) {
                    $Setting = Setting::find($request['id']);
                    $Setting->name = 'Privacy Policy';
                    $Setting->file = $fileName;
                    $Setting->type = 2;
                    if ($Setting->update()) {
                        return redirect()->route('privacy_policy')
                            ->with('success', 'Privacy Policy uploaded successfully!');
                    } else {
                        return redirect()->route('privacy_policy')
                            ->with('error', 'Failed to upload Privacy Policy!');
                    }
                }
            } else {
                return redirect()->route('privacy_policy')
                    ->with('error', 'Failed to upload Privacy Policy!');
            }
        } else {
            return redirect()->route('privacy_policy')
                ->with('error', 'Please upload file!');
        }
    }

    /*===============================
    email setting for mail 
    =================================
     */

    public function email_setting(Request $request)
    {
        $active = 'email_setting';
        try{
            if ($request->isMethod('GET')) {
                $email_data = EmailSetting::where('status', 1)->first();
                return view('admin.faq.email_setting', compact('email_data', 'active'));
            }
            if ($request->isMethod('POST')) {
                if(!empty($request->id)){
                    EmailSetting::update($request->all());
                }else{
                    EmailSetting::create($request->all());
                }
                return redirect()->route('email_setting')->with('success', "Email added successfully");
                // print_r($request->all());;die;
            }
        }catch (Exception $ex) {
            return redirect()->route('email_setting')->with('error', $ex->getMessage());
        }
    }

}
