<?php

namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use App\Libraries\JWT;
use App\Models\Apikeys;
use App\Models\Users;

class ValidateApiKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $token_valid = 'no';
            $token = $request->bearerToken(); //Obtaining the token from the request
            if(isset($token)) {
                $server_key = env('JWT_SECRET');
                $token_data = JWT::decode($token, $server_key);
                $user_id = $token_data->user_id;
                $user_exists = Users::where('id', $user_id)->where('status', 'active')->exists(); //Checking if the user id is valid
                if($user_exists) {
                    $valid_key =Apikeys::where('user_id', $user_id)->where('api_key', $token)->exists(); //Checking if the token belongs to the user
                    if($valid_key) {
                        $token_valid = 'yes';
                    }
                }

                if ($token_valid === 'yes') {
                    return $next($request);
                }
                else {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Unauthorized',
                    ], 401);
                }
            }
            else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized',
                ], 401);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid api key',
            ], 401);
        }
    }
}

