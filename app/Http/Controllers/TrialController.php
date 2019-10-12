<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class TrialController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
     public function __construct(\Illuminate\Http\Request $request) {
         $this->request = $request;
     }

     public function try(Request $request) {
       $input  = $this->request->all();
       $xml = new \SimpleXMLElement('<root/>');
       $array = array_flip($input);
       array_walk_recursive($array, array ($xml, 'addChild'));
       return $xml->asXML();
     }

}
