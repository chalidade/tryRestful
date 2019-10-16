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
      $table  = $input["table"];

      if ($action == "saveform") {
        $this->validasi($table, $request);
      }

      return $this->$action($input);
    }

    function view($input) {
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

    function delete($input) {
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

    function save($input) {
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

    function saves($input) {
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

    function savelinked($input) {
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

    function headerdetail($input) {
      // Header
      $header          = $input["header"];
      $parameter       = $input['input'];
      $detail          = $input["detail"];
      $data            = DB::table($header)->insert($parameter);

      // Get ID
      $latest          = DB::table($header)->orderBy('id', 'desc')->take(1)->get();
      $decode          = json_decode($latest, true);
      $id              = array('id' => $decode[0]["id"],);
      $res['header']   = $decode;

      // Detail
      $detail          = $input["detail"];
      for ($i=0; $i < count($detail); $i++) {
        $secondary     = $detail[$i]["table"];
        $combine       = $id+$detail[$i]["input"];
        $data          = DB::table($secondary)->insert($combine);
        $res['detail'][$secondary] = $combine;
      };

      return response()->json($res);
    }

    function edit($input) {
      $table  = $input["table"];
      $param  = $input["value"];
      $where  = $input["where"];
      $query  = DB::table($table)->where($where)->update($param);

      return response("Edit Berhasil");
    }

    function edits($input) {
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

    function checkData($input) {
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

    function other($input) {
      $raw   = $input["raw"];
      if (isset($input['value'])) {
        $param = $input["value"];
        $table = DB::select($raw, $param);
      } else {
        $table = DB::select($raw);
      }
      return response($table);
    }

    function xml($input) {
        $xml = new \SimpleXMLElement('<root/>');
        $array = array_flip($input);
        array_walk_recursive($array, array ($xml, 'addChild'));
        return $xml->asXML();
    }

    function saveedit($input) {
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

    // Move Ke Server
    // Index
    function index($input) {
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

    // View
    public function saveform($input) {
      return response("Berhasil");
    }

    function validasi($table, $request) {
      $latest   = DB::table('validation')->where('nama_tbl', 'like', $table."%")->select(["field", "mandatori"])->get();
      $decode   = json_decode($latest, true);
      $s        = array();
      for ($i=0; $i < count($decode); $i++) {
      $s[$decode[$i]["field"]] = $decode[$i]["mandatori"];
      };

      $this->validate($request, $s);
    }

    // //Helper
    // public function BasicShow($connect) {
    //   $data     = $connect->get();
    //   return $data;
    // }
}


// Mengubah dari form ke json itu perlu validasi
/* cek tipe data : string numerik, date
ketika form pke json ke string di potong
semua harus dirubah kalo tidak numerik jadi nol error validate
date bentuk apaun harus siap ke database
untuk error

paing panjang alamat
untuk table validasi
Nama table, field name, mandatori, message.
mandatori itu 0 dan 1
Date - File
validate string format tanggal atau bukan
buat funcrion buat hapus file sama upload file

Kalo nggak diakses diluar controller / hanya di controller saja bukan dari route dibuat function

*/
