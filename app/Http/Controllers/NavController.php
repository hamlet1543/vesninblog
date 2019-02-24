<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Auth;
use App\User;
use Storage;

class NavController extends Controller
{
    public function recurs($folders){
        $arr=[];
        foreach($folders as $folder){
            $folders_in = \App\Nav::where('user','=', Auth::user()->id)->where('parent_id','=',$folder->id)->orderBy('name')->get();
            if(count($folders_in)>0){
               $arr[]=$folder;
               $arr = array_merge($arr, $this->recurs($folders_in));
            }
            else
                $arr[]=$folder;
        }
        return $arr;
    }    


    public function nav_main_edit(Request $request){
        $validator = \Validator::make($request->all(),[
            'name'
        ]);
        if($validator->fails()){
            return response(['status'=>'error', 'error'=>$validator->fails()], 200);
        }
              
        Auth::user()->task = $request->name;;
        Auth::user()->save();

        return response(['status'=>'ok'], 200);
    }

    public function nav_edit(\App\Nav $nav, Request $request){
        $validator = \Validator::make($request->all(),[
            'name',
        ]);
        if($validator->fails()){
           return response(['status'=>'error', 'error'=>$validator->fails()], 200);
        }

        $nav->name = $request->name;
        $nav->save();

        $navs = $this->recurs(\App\Nav::where('user','=', Auth::user()->id)->where('parent_id','=',0)->orderBy('name')->get());
        $navs = json_encode($navs);

        return response(['status'=>'ok','navs'=> $navs], 200);
    }

    public function nav_add(Request $request){
        $validator = \Validator::make($request->all(),[
            'name',
            'parent_id',
            'level'
        ]);
        if($validator->fails()){
            return response(['status'=>'error', 'error'=>$validator->fails()], 200);
        }

        $nav = new \App\Nav;
        $nav->user = Auth::user()->id;
        $nav->name = $request->name;
        $nav->parent_id = $request->parent_id;
        $nav->level = $request->level;
        $nav->save();

        $navs = $this->recurs(\App\Nav::where('user','=', Auth::user()->id)->where('parent_id','=',0)->orderBy('name')->get());
        $navs = json_encode($navs);
        
        return response(['status'=>'ok','navs'=> $navs], 200);
    }

    public function nav_delete(\App\Nav $nav){
        $folders = $this->recurs(\App\Nav::where('user','=', Auth::user()->id)->where('parent_id','=',$nav->id)->orderBy('name')->get());
        foreach($folders as $folder){
            $tasks = \App\Task::where('nav','=', $folder->id)->get();
            foreach ($tasks as $task) {
                $task->delete();
            }
            Storage::deleteDirectory('public/uploads/user_'.Auth::user()->id.'/nav_'.$folder->id);
            $folder->delete();

        }
        $tasks = \App\Task::where('nav','=', $nav->id)->get();
        foreach ($tasks as $task) {
            $task->delete();
        }
        Storage::deleteDirectory('public/uploads/user_'.Auth::user()->id.'/nav_'.$nav->id);
        $nav->delete();
    
        $navs = $this->recurs(\App\Nav::where('user','=', Auth::user()->id)->where('parent_id','=',0)->orderBy('name')->get());
        $navs = json_encode($navs);
 
        return response(['status'=>'delete','navs'=> $navs], 200);
    }
    public function name_edit(Request $request){
        $validator = \Validator::make($request->all(),[
           'name' => ['required', 'string', 'max:255']            
        ]);
        $status = 'success';
        $text = 'login успешно изменен';
        if($validator->fails()){
            $status = 'danger';
            $text = 'Не корректный login';
        }else{
            $user = Auth::user();
            $user->name = $request->name;
            $user->save();
            
        }
 
        return response(['status'=>$status,'text'=>$text], 200);
    }
    public function password_edit(Request $request){
        $validator = \Validator::make($request->all(),[
           'new' => ['required', 'string', 'min:6'],            
           'current' => ['required', 'string', 'min:6']          
        ]);
        $status = 'success';
        $text = 'пароль успешно изменен';
        if($validator->fails()){
            $status = 'danger';
            $text = 'Не корректный пароль';
        }else{
            $user = Auth::user();
            if(!Hash::check($request->current, $user->password))
            {
                $status = 'danger';
                $text = 'Текущий пароль введен неправильно';
            }
            else{
                $user->password =  Hash::make($request->new);
                $user->save();
            }
            
        }
 
        return response(['status'=>$status,'text'=>$text], 200);
    }
    public function ava_upload(Request $request){
        $avatar = $request->file('image')->store('uploads/user_'.Auth::user()->id.'/avatar', 'public');
        $avatar = "/storage/".$avatar;
        $user = Auth::user();
        $user->avatar = $avatar;
        $user->save();
        return response(['avatar'=>$avatar], 200);
    }
    public function ava_delete(Request $request){
        $avatar = collect(Storage::files('public/uploads/user_'.Auth::user()->id.'/avatar'))->map(function($file) {
            return Storage::delete($file);
        });

        $user = Auth::user();
        $user->avatar = null;
        $user->save();

        return response(['status'=>'delete'], 200);
    }

}
