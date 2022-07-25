<?php

namespace App\Http\Controllers;

use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsersAuthController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, $id)
    {
        try {
            $user = Users::findOrFail(encrypt_decrypt('decrypt', $id));
            Auth::login($user);
            $request->session()->put('is_authorized', 'true');
            return redirect()->route('dashboard');
        } catch (Exception $ex) {
            abort(404);
        }
    }
    /**
     * redirect user to p2b
     */
    public function redirects(Request $request)
    {
        try {
            $url = P2B_BASE_URL . "/users/redirects/" . encrypt_decrypt('encrypt', auth()->user()->id);
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect($url);
        } catch (Exception $ex) {
            return redirect()->back()->with('error', $ex->getMessage());
        }
    }
}
