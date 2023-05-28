<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libraries\JWT;
use Carbon\Carbon;
use App\Models\Apikeys;
use App\Models\Users;
use Session;

class ApikeyController extends Controller
{
    /**
     * API to generate new api key
     *
     * @param Request $request
     * @return void
     */
    public function generate_api_key(Request $request)
    {
        try {
            $user_id = Session('userId');
            $jwt = new JWT();
            $token = array();
            $token['user_id'] = $user_id;
            $token['generated_on'] = Carbon::now();
            $server_key = env('JWT_SECRET');
            $json_token = $jwt->encode($token, $server_key);
            $data = array(
                'api_key' => $json_token
            );

            $api_row_id = Apikeys::select('id')->where('user_id', $user_id)->exists();
            if(!$api_row_id) {
                $new_data = array(
                    'user_id' => $user_id,
                    'api_key' => $json_token
                );
                $api_key_created = DB::table('api_keys')->insert($new_data);
            }
            else {
                $api_key_created = Apikeys::where('user_id', $user_id)
                    ->update(['api_key' => $json_token]);
            }

            if ($api_key_created) {
                return response()->json(["success" => true, 'message' => 'Api key generated successfully', 'data' => $data], 200);
            }
            else {
                return response()->json(["success" => false,  'message' => 'Api key was not generated successfully'], 400);
            }
        } catch (\Throwable $th) {
            echo 'Message: ' .$th->getMessage();
        }
    }
}
