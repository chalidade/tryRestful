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

    public function api(Request $request) {
      $input  = $this->request->all();
      $action = $input["action"];
      return $this->$action($input);
    }

    public function view($input) {
      $table  = $input["table"];
      if(isset( $input['parameter']["selected"])){
        $e          = $input['parameter']["selected"];
        for ($i=0; $i < count($e); $i++) {
          $column[] = $e[$i];
        }
      }

      // If Parameter Not Null
      if (isset($input['parameter']) && isset($input['parameter']["data"])) {
        $a      = $input['parameter']["data"];
        $b      = $input['parameter']["value"];
        $c      = $input['parameter']["operator"];
        $db      = DB::table($table);
        // Get Parameter
        for ($i=0; $i < count($a); $i++) {
          if (isset($input['parameter']["type"])) {
            $d      = $input['parameter']["type"];
            if ($d == "or") {
              if ($c[$i] != "like") {
                $result[] = array($a[$i],$c[$i],$b[$i]);
                if(isset( $input['parameter']["selected"])){
                  $data     = $db->orWhere($result)->select($column)->get();
                } else {
                  $data     = $db->orWhere($result)->get();
                }
              } else {
                if(isset( $input['parameter']["selected"])){
                  $data     = $db->orwhere($a[$i], 'like', $b[$i].'%')->select($column)->get();
                } else {
                  $data     = $db->orwhere($a[$i], 'like', $b[$i].'%')->get();
                }
              }
            } else if($d == "and") {
              $result[] = array($a[$i],$c[$i],$b[$i]);
              $data     = $db->Where($result)->select($column)->get();
            }
          } else {
            $data     = $db->orwhere($a[$i], 'like', $b[$i].'%')->get();
          }
        }

        // If Agregate On
        if(isset($input['parameter']["aggregate"])) {
          $f      = $input['parameter']["aggregate"];
           if($f[0] == "count") {
             return response()->json($data->count($f[1]));
           } else if($f[0] == "max") {
             return response()->json($data->max($f[1]));
           } else if($f[0] == "avg") {
             return response()->json($data->avg($f[1]));
           } else if($f[0] == "sum"){
             return response()->json($data->sum($f[1]));
           } else if($f[0] == "min") {
             return response()->json($data->min($f[1]));
           } else {
             return response("Agregate Wrong");
           }
        } else {
          return response()->json($data);
        }
      }
      // If Parameter NULL
      else {
        if(isset( $input['parameter']["selected"])){
            $data   = DB::table($table)->select($column)->get();
          } else {
            $data   = DB::table($table)->get();
          }

            // If Agregate
            if(isset($input['parameter']["aggregate"])) {
              $f      = $input['parameter']["aggregate"];
               if($f[0] == "count") {
                 return response()->json($data->count($f[1]));
               } else if($f[0] == "max") {
                 return response()->json($data->max($f[1]));
               } else if($f[0] == "avg") {
                 return response()->json($data->avg($f[1]));
               } else if($f[0] == "sum"){
                 return response()->json($data->sum($f[1]));
               } else if($f[0] == "min") {
                 return response()->json($data->min($f[1]));
               } else {
                 return response("Agregate Wrong");
               }
            } else {
              return response()->json($data);
            }
          }
        }

    public function delete($input) {
      $table   = $input["table"];
      if (isset($input['parameter']) AND isset($input['parameter']["data"])) {
        $a     = $input['parameter']["data"];
        $b     = $input['parameter']["value"];
        for ($i=0; $i < count($a); $i++) {
          $result[] = array($a[$i],'=',$b[$i]);
        }
        $data   = DB::table($table)->Where($result)->delete();
        // $view   = DB::table($table)->get();
        return response("Berhasil Hapus Data");
      } else if($input['parameter'] == 'truncate') {
        $data   = DB::table($table)->truncate();
        return response("Clear All Data in Table $table");
      } else {
        return response("Input JSON / Parameter Salah");
      }
    }

    public function save($input) {
      $table      = $input["table"];
      $parameter  = $input['parameter'];
      $jumlah     = count($parameter);
      if ($jumlah > 1) {
        for ($i   = 0; $i < $jumlah; $i++) {
          $data   = DB::table($table)->insert($parameter[$i]);
        }
      } else {
          $data   = DB::table($table)->insert($parameter);
      }
      return response($parameter);
    }

    public function saves($input) {
      $countData    = count($input["data"]);
      for ($i=0; $i < $countData; $i++) {
      $table        = $input["data"][$i]["table"];
      $parameter    = $input["data"][$i]["parameter"];
      $jumlah       = count($input["data"][$i]["parameter"]);
        if ($jumlah > 1) {
          for ($j   = 0; $j < $jumlah; $j++) {
            $data   = DB::table($table)->insert($parameter[$j]);
          }
        } else {
            $data   = DB::table($table)->insert($parameter);
        }
      }
      return response()->json("Berhasil Simpan data");
    }

    public function savelinked($input) {
      $primaryTable = $input["table"];
      $anotherTable = $input["linkto"];
      $parameter    = $input["parameter"];
      $linkby       = $input["linkby"];
      $linkedvalue  = $parameter[$linkby];
      $dataa        = $input["data"];
      $datab        = array($linkby => $linkedvalue,);
      $combine      = $datab+$dataa;

      $data         = DB::table($primaryTable)->insert($parameter);
      $data         = DB::table($anotherTable)->insert($combine);

      return response("Berhasil Save");
    }

    public function edit($input) {
      $table  = $input["table"];
      $param  = $input["value"];
      $where  = $input["where"];
      $query  = DB::table($table)->where($where)->update($param);

      return response("Edit Berhasil");
    }

    public function edits($input) {
      $parameter = $input["data"];
      $countData = count($parameter);
      for ($i=0; $i < $countData; $i++) {
        $table  = $parameter[$i]["table"];
        $where  = $parameter[$i]["where"];
        $value  = $parameter[$i]["value"];
        $query  = DB::table($table)->where($where)->update($value);
      }

      return response("Update Berhasil");
    }

    public function checkData($input) {
      $table  = $input["table"];
      if (isset($input['parameter'])) {
        $a = $input['parameter']["data"];
        $b = $input['parameter']["value"];
        $c = $input['parameter']["check"];
        for ($i=0; $i < count($a); $i++) {
          $result[] = array($a[$i],$b[$i]);
        }
        $data       = DB::table($table)->where($result);
        if ($c == "exist") {
          return response()->json($data->exists());
        } else if($c == "noexist") {
          return response()->json($data->doesntExist());
        } else {
          return response("Check Parameter Salah");
        }
      } else {
        return response("Input JSON tidak lengkap");
      }

    }

    public function other($input) {
      $raw   = $input["raw"];
      if (isset($input['value'])) {
        $param = $input["value"];
        $table = DB::select($raw, $param);
      } else {
        $table = DB::select($raw);
      }
      return response($table);
    }

    public function xml($input) {
        $xml = new \SimpleXMLElement('<root/>');
        $array = array_flip($input);
        array_walk_recursive($array, array ($xml, 'addChild'));
        return $xml->asXML();
    }
}
