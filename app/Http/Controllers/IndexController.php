<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IndexController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function api(Request $request) {
      $input  = $this->request->all();
      $action = $input["action"];
      return $this->$action($input);
    }

      public function view($input) {
        // Inisialisasi
        $connect  = \DB::table($input["table"])->orderBy($input['orderby'][0], $input['orderby'][1]);

        // Search Parameter Exist
        if (isset($input['search'])) {
          $connect->orwhere($input['search'][0], 'like', $input['search'][1].'%');
        }

        $result   = $connect->skip($input['pagination'][0])->take($input['pagination'][1])->get();
        $count   = $connect->count();

        return response()->json(["result"=>$result, "count"=>$count]);
    }
}
