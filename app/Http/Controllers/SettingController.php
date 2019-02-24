<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Auth;

class SettingController extends Controller
{
   public function angle_edit(Request $request){
        $validator = \Validator::make($request->all(),[
            'value'
        ]);
        if($validator->fails()){
            return response(['status'=>'error', 'error'=>$validator->fails()], 200);
        }
       
        Auth::user()->angle = $request->value;
        Auth::user()->save();

        return response(['status'=>'ok'], 200);
    }
    
    public function distance_edit(Request $request){
        $validator = \Validator::make($request->all(),[
            'value'
        ]);
        if($validator->fails()){
            return response(['status'=>'error', 'error'=>$validator->fails()], 200);
        }
        
        Auth::user()->distance = $request->value;
        Auth::user()->save();

        return response(['status'=>'ok'], 200);
    }

}
