<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Users;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use App\Mail\ForgotPasswordMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class LoginController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('userisredirected');
    }

    public function login(Request $request)
    {
        try{
            if ($request->isMethod('GET')) {
                $terms_conditions = Setting::where('type', 1)->first();
                return view('admin.login', compact('terms_conditions'));
            }
            if ($request->isMethod('POST')) {
                // die('here');
                $request->validate([
                    'email' => 'required|email',
                    'password' => 'required',
                ]);

                $password = Hash::make($request->password);

                $hub_user = Users::with(['users_details' => function($query){
                    $query->select('id', 'i_ref_user_id','i_ref_company_id', 'i_ref_bu_id', 'i_status');
                }])->select('id', 'email', 'user_type', 'password', 'hash_password','i_status')->where('email', $request->email)->where('user_type', $request->user_type)->first();

                $remember_me  = ( !empty( $request->remember_me ) )? TRUE : FALSE;

                if(!empty($hub_user) && !empty($hub_user->users_details) && $hub_user->users_details->i_status == 0){

                    return redirect()->route('login')->with('error','Your Account has been Deactivated.Contact Admin at e-mail: admin@gmail.com ');

                }elseif(!empty($hub_user) && !empty($hub_user->users_details) && $hub_user->users_details->i_status == 1){
                    if (!empty($hub_user) && Hash::check($request->password, $hub_user->hash_password)) {
                        //password matched. Log in the user 
                            Auth::login($hub_user, $remember_me);
                            return redirect()->route('dashboard');
                    }else{

                        $response = Http::post(P2B_BASE_URL.'/bnpsrv1/Users/login_hub_form.json', [
                            'email' => $request->email,
                            'password' => $request->password,
                            'user_type' => $request->user_type
                        ]);
                        $response_data = json_decode((string) $response->getBody(), true);
                        $data = collect($response_data);

                        // pr($data);die;
                        if(!empty($data['response']) && $data['status'] == 1){
                            $user_id = $data['response']['User']['id'];
                            $input['hash_password'] = $password;
                            $user = Users::findOrFail($user_id);
                            if(empty($user->hash_password)){
                                $user->update($input);
                            }
                            
                            if (Hash::check($request->password, $user->hash_password)) {
                                //password matched. Log in the user 
                                    Auth::login($user, $remember_me);
                                    return redirect()->route('dashboard');
                            }else{ 
                                return redirect()->route('login')->with('error','Please enter valid login details!');
                            }

                        }else{ 
                            return redirect()->route('login')->with('error','Please enter valid login details!');
                        }
                    }
                }else{
                    return redirect()->route('login')->with('error','Unable to login! Please try again.');
                }

            }
        }catch (Exception $ex) {
            return redirect()->route('login')->with('error', $ex->getMessage());
        }
    }

    public function supplier_login(Request $request)
    {
        try{
            if ($request->isMethod('GET')) {
                $terms_conditions = Setting::where('type', 1)->first();
                return view('admin.supplier-login', compact('terms_conditions'));
            }
        }catch (Exception $ex) {
            return redirect()->route('supplier.login')->with('error', $ex->getMessage());
        }
    }

    /**
     * Auth user logout
     * @param Request $request
     * @return redirect
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        // return redirect(P2B_BASE_URL);
        return redirect()->route('login');
    }

    /**
     * forgot password
     * @param Request $request
     * @return redirect
    */
    public function forgot_password(Request $request){
        try{
            if ($request->isMethod('GET')) {
                return view('admin.forgot-password');
            }
            if ($request->isMethod('POST')) {
                $hub_user = Users::select('id', 'email', 'vc_fname', 'vc_mname', 'vc_lname')->where('email', $request->email)->first();
                if(!empty($hub_user)){
                    
                    $hub_user['vc_password_token'] = Str::random(30);
                    $hub_user->update();

                    $when = now()->addMinutes(1);
                        $data = array(
                            'id' => $hub_user['id_encrypted'],
                            'name' => $hub_user['vc_fname'] . ' ' . $hub_user['vc_mname'] . ' ' . $hub_user['vc_lname'],
                            'email' => $request->email,
                            'token' => $hub_user['vc_password_token']
                        );

                        $mail_id = $hub_user['email'];
                        $sendMail = new ForgotPasswordMail($data);
                        $mail = Mail::to($mail_id)->later($when, $sendMail);

                        return redirect()->route('login')->with('success', 'A reset password link has been sent to your registered email.Please reset your password using given link.');
                }else{
                    return redirect()->route('forgot_password')->with('error', 'Email address not associated with any account.');
            }            
        }
        }catch (Exception $ex) {
            return redirect()->route('login')->with('error', $ex->getMessage());
        }
    }

    /**
     * reset password
     * @param Request $request
     * @return redirect
    */

    public function reset_password(Request $request, $id, $token){

        try{
            $id = encrypt_decrypt('decrypt', $id);
            $user = Users::select('id', 'email', 'vc_password_token', 'hash_password')->where('id', $id)->where('vc_password_token', $token)->first();

            if ($request->isMethod('GET')) {
                

                if(!empty($user)){
                    return view('admin.reset-password', compact('active', 'user'));
                }else{
                    return redirect()->route('login')->with('error', 'Your Reset Password is now expired');
                }
            }
            if ($request->isMethod('POST')) {

                if(!empty($user)){
                    $password = Hash::make($request->password);

                    $input['hash_password'] = $password;
                    $input['vc_password_token'] = '';
                    if($user->update($input)){
                        return redirect()->route('login')->with('success', "Your password reset successfully. Please login using new password");
                    }else{
                        return redirect()->route('login')->with('error', "Unable to change password!");
                    }
                }else{
                    return redirect()->route('login')->with('error', "User doesn't exist!");
                }
            }

        }catch (Exception $ex) {
            return redirect()->route('login')->with('error', $ex->getMessage());
        }    
        
    }
    
}
