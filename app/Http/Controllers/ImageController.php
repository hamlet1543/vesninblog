<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Auth;
use Storage;

class ImageController extends Controller
{

    public function image_upload(Request $request){
        $validator = \Validator::make($request->all(),[
            'nav'
        ]);
        if($validator->fails()){
            return response(['status'=>'error', 'error'=>$validator->fails()], 200);
        }



        $path = $request->file('image')->store('uploads/user_'.Auth::user()->id.'/nav_'.$request->nav, 'public');
        $images = collect(Storage::files('public/uploads/user_'.Auth::user()->id.'/nav_'.$request->nav))->map(function($file) {
            return Storage::url($file);
        });
        return response(['images'=>$images], 200);
    }
    public function image_delete(Request $request){
        $validator = \Validator::make($request->all(),[
            'url',
            'nav'
        ]);
        if($validator->fails()){
            return response(['status'=>'error', 'error'=>$validator->fails()], 200);
        }

        if(isset($request->url))
           unlink(public_path($request->url));

        $images = collect(Storage::files('public/uploads/user_'.Auth::user()->id.'/nav_'.$request->nav))->map(function($file) {
            return Storage::url($file);
        });

        return response(['images'=>$images], 200);
    }

}
