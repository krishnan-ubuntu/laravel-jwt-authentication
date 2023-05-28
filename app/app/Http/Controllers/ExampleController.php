<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Models\Apikeys;
use App\Models\Users;

class ExampleController extends Controller
{

    /**
     * Example API
     *
     * @param Request $request
     * @return void
     */
    public function test_method(Request $request)
    {
        /*
        test api returning dummy json
        */
        $data = array(
            'name' => 'Tom Cruise', 
            'localtion' => 'Hollywoord'
        );
        return response()->json($data, 200);
    }

}
