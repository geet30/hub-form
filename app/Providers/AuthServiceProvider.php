<?php

namespace App\Providers;

use App\Models\Users;
use App\Models\UserToken;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use \Firebase\JWT\JWT;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {

        $this->registerPolicies();

        /**
         * @var Authentication For Api User
         * @header Token
         * @return Auth::user() | null 
         */

        Auth::viaRequest('custom-token', function ($request) {

            $token = $request->header('Token');
            $secret = config("jwt.key");

            JWT::$leeway = config("jwt.leeway");

            if (config('app.env') != "production") {
                if (!$request->hasHeader('Token')) {
                    \Log::error("Jwt token missing");
                }
                if (!env('JWT_KEY')) {
                    \Log::error("Jwt key missing");
                }
            }

            if ($token && $secret && strlen($token) > 0) {
                try {
                    $jwtCheck = JWT::decode(trim($token), $secret, array("HS256"));
                    if (!$jwtCheck) {
                        throw new \Exception;
                    }
                    $checkExpiration = UserToken::on('mysql2')->select('id')->where('i_ref_user_id', $jwtCheck->id)->where('expiration', '>=', date("Y-m-d H:i:s"))->firstOrfail();
                    if (!$checkExpiration) {
                        throw new \Exception;
                    }
                    return Users::findOrfail($jwtCheck->id);
                } catch (\Exception $e) {
                    return null;
                } catch (\DomainException $ex) {
                    return null;
                } catch (\Firebase\JWT\SignatureInvalidException $ex) {
                    return null;
                } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $ex) {
                    return null;
                }
            }
            return null;
        });
    }
}
