<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Auth;

class SettingController extends Controller
{
   public function angle_edit(Request $request){
        $validator = \Validator::make($request->all(),[
            'angle'
        ]);
        if($validator->fails()){
            return redirect('/')
                ->withInput()
                ->withErrors($validator);
        }
        $angle = \App\Setting::where('user','=', Auth::user()->id)->where('name','=', 'angle')->first();

              
        $angle->property = $request->angle;;
        $angle->save();

        return response(['status'=>'ok'], 200);
    }
    
    public function distance_edit(Request $request){
        $validator = \Validator::make($request->all(),[
            'distance'
        ]);
        if($validator->fails()){
            return redirect('/')
                ->withInput()
                ->withErrors($validator);
        }
        $distance = \App\Setting::where('user','=', Auth::user()->id)->where('name','=', 'distance')->first();

              
        $distance->property = $request->distance;;
        $distance->save();

        return response(['status'=>'ok'], 200);
    }

}
