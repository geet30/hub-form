<?php

namespace App\Http\Middleware;

use App\Models\UserToken;
use App\Models\Users;
use Closure;
use Illuminate\Http\Response;
use \Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticateApiUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {

            if (!$request->hasHeader('Token')) {
                \Log::error("Jwt token missing");
                // return $next($request);
                return response(["status" => Response::HTTP_UNAUTHORIZED, "message" => "Missing Token"], Response::HTTP_UNAUTHORIZED);
            }

            $jwt = $request->header('Token');

            \Log::error("Jwt key missing :- $jwt");

            // return $next($request);


            $key = env('JWT_KEY');
            
            if (!$key) {
                \Log::error("Jwt key missing");
                return response(["status" => Response::HTTP_INTERNAL_SERVER_ERROR, "message" => "Jwt key missing"], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            $jwtCheck = JWT::decode(trim($jwt), $key, array('HS256'));

            


            if (isset($jwtCheck) && http_response_code() == 200) {
                $checkExpiration = UserToken::where('i_ref_user_id', $jwtCheck->id)->where('expiration', '>=', date("Y-m-d H:i:s"))->firstOrfail();
                if ($checkExpiration) {
                    Auth::loginUsingId($jwtCheck->id);
                    // $user = Users::first();
                    // $request->user = Users::first();
                    return $next($request);
                }
            }
            return response(["status" => Response::HTTP_UNAUTHORIZED, "message" => "Expired token"], Response::HTTP_UNAUTHORIZED);
        } catch (\DomainException $ex) {
            return response(["status" => Response::HTTP_UNAUTHORIZED, "message" => "Not Authorized"], Response::HTTP_UNAUTHORIZED);
        } catch (\Firebase\JWT\SignatureInvalidException $ex) {
            return response(["status" => Response::HTTP_UNAUTHORIZED, "message" => $ex->getMessage()], Response::HTTP_UNAUTHORIZED);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $ex) {
            return response(["status" => Response::HTTP_UNAUTHORIZED, "message" => "Expired token"], Response::HTTP_UNAUTHORIZED);
        }
    }
}