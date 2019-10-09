<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Tongkang;
use DB;

class TongkangController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(\Illuminate\Http\Request $request) {
        $this->request = $request;
    }

    public function json(Request $request) {
      $input  = $this->request->all();
      $action = $input["action"];
      $table  = $input["table"];
      return $this->$action($table, $input);
    }

    public function view($table, $input) {
      $data   = DB::table($table)->get();
      return response()->json($data);
    }

    public function delete($table, $input) {
      return response("Delete $table");
    }





    // $action = $input['action'];
    // $table  = $input['table'];
    // public function view($table, $input) {
    //   $table  = $input['table'];
    //
    //   if(isset( $input['parameter']["selected"])){
    //     $e      = $input['parameter']["selected"];
    //     for ($i=0; $i < count($e); $i++) {
    //       $column[]     = $e[$i];
    //     }
    //   }
    //
    //   // If Parameter Not Null
    //   if (isset($input['parameter']) && isset($input['parameter']["data"]))
    //   {
    //     $a      = $input['parameter']["data"];
    //     $b      = $input['parameter']["value"];
    //     $c      = $input['parameter']["operator"];
    //     $db      = DB::table($table);
    //     // Get Parameter
    //     for ($i=0; $i < count($a); $i++) {
    //       if (isset($input['parameter']["type"])) {
    //         $d      = $input['parameter']["type"];
    //         if ($d == "or") {
    //           if ($c[$i] != "like") {
    //             $result[] = array($a[$i],$c[$i],$b[$i]);
    //             if(isset( $input['parameter']["selected"])){
    //               $data     = $db->orWhere($result)->select($column)->get();
    //             } else {
    //               $data     = $db->orWhere($result)->get();
    //             }
    //           } else {
    //             if(isset( $input['parameter']["selected"])){
    //               $data     = $db->orwhere($a[$i], 'like', $b[$i].'%')->select($column)->get();
    //             } else {
    //               $data     = $db->orwhere($a[$i], 'like', $b[$i].'%')->get();
    //             }
    //           }
    //         } else if($d == "and") {
    //           $result[] = array($a[$i],$c[$i],$b[$i]);
    //           $data     = $db->Where($result)->select($column)->get();
    //         }
    //       } else {
    //         $data     = $db->orwhere($a[$i], 'like', $b[$i].'%')->get();
    //       }
    //     }
    //
    //     // If Agregate On
    //     if(isset($input['parameter']["aggregate"])) {
    //       $f      = $input['parameter']["aggregate"];
    //        if($f[0] == "count") {
    //          return response()->json($data->count($f[1]));
    //        } else if($f[0] == "max") {
    //          return response()->json($data->max($f[1]));
    //        } else if($f[0] == "avg") {
    //          return response()->json($data->avg($f[1]));
    //        } else if($f[0] == "sum"){
    //          return response()->json($data->sum($f[1]));
    //        } else if($f[0] == "min") {
    //          return response()->json($data->min($f[1]));
    //        } else {
    //          return response("Agregate Wrong");
    //        }
    //     } else {
    //       return response()->json($data);
    //     }
    //   }
    //   // If Parameter NULL
    //   else {
    //     if(isset( $input['parameter']["selected"])){
    //         $data   = DB::table($table)->select($column)->get();
    //       } else {
    //         $data   = DB::table($table)->get();
    //       }
    //
    //         // If Agregate
    //         if(isset($input['parameter']["aggregate"])) {
    //           $f      = $input['parameter']["aggregate"];
    //            if($f[0] == "count") {
    //              return response()->json($data->count($f[1]));
    //            } else if($f[0] == "max") {
    //              return response()->json($data->max($f[1]));
    //            } else if($f[0] == "avg") {
    //              return response()->json($data->avg($f[1]));
    //            } else if($f[0] == "sum"){
    //              return response()->json($data->sum($f[1]));
    //            } else if($f[0] == "min") {
    //              return response()->json($data->min($f[1]));
    //            } else {
    //              return response("Agregate Wrong");
    //            }
    //         } else {
    //           return response()->json($data);
    //         }
    //       }
    //     }
    //
    // public function delete($table, $input) {
    //   $table = $input['table'];
    //   if (isset($input['parameter']) AND isset($input['parameter']["data"])) {
    //     $a     = $input['parameter']["data"];
    //     $b     = $input['parameter']["value"];
    //     for ($i=0; $i < count($a); $i++) {
    //       $result[] = array($a[$i],'=',$b[$i]);
    //     }
    //     $data   = DB::table($table)->Where($result)->delete();
    //     // $view   = DB::table($table)->get();
    //     return response("Berhasil Hapus Data");
    //   } else if($input['parameter'] == 'truncate') {
    //     $data   = DB::table($table)->truncate();
    //     return response("Clear All Data in Table $table");
    //   } else {
    //     return response("Input JSON / Parameter Salah");
    //   }
    // }
    //
    // public function save($table, $input) {
    //   $parameter  = $input['parameter'];
    //   $jumlah     = count($parameter);
    //   if ($jumlah > 1) {
    //     for ($i   = 0; $i < $jumlah; $i++) {
    //       $data   = DB::table($table)->insert($parameter[$i]);
    //     }
    //   } else {
    //       $data   = DB::table($table)->insert($parameter);
    //   }
    //   return response("Data Berhasil Disimpan");
    // }
    //
    // public function edit($table, $input) {
    //   $id   = $input['id'];
    //   return response("Edit $table");
    // }
    //
    // public function checkData($table, $input) {
    //   if (isset($input['parameter'])) {
    //     $a = $input['parameter']["data"];
    //     $b = $input['parameter']["value"];
    //     $c = $input['parameter']["check"];
    //     for ($i=0; $i < count($a); $i++) {
    //       $result[] = array($a[$i],$b[$i]);
    //     }
    //     $data       = DB::table($table)->where($result);
    //     if ($c == "exist") {
    //       return response()->json($data->exists());
    //     } else if($c == "noexist") {
    //       return response()->json($data->doesntExist());
    //     } else {
    //       return response("Check Parameter Salah");
    //     }
    //   } else {
    //     return response("Input JSON tidak lengkap");
    //   }
    //
    // }


}
