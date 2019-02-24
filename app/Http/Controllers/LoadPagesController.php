<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Auth;
use App;
use App\User;
use Storage;

class LoadPagesController extends Controller
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

    public function index_tasks(){
        $user = Auth::user();
        $navs = $this->recurs(\App\Nav::where('user','=', Auth::user()->id)->where('parent_id','=',0)->orderBy('name')->get());
        $navs = json_encode($navs);

        $tasks = \App\Task::where('user','=', $user->id)->where('nav','=', 0)->where('done','=', 0)->orderBy('created_at', 'asc')->get();
        $tasksDone = \App\Task::where('user','=', $user->id)->where('nav','=', 0)->where('done','=', 1)->orderBy('created_at', 'asc')->get();
        $images = collect(Storage::files('public/uploads/user_'.Auth::user()->id.'/nav_0'))->map(function($file) {
            return Storage::url($file);
        });

        $angle = Auth::user()->angle;
        $distance = Auth::user()->distance;

        return view('tasks',[
            'navs'=> $navs,
            'user'=>$user,
            'tasks'=>$tasks,
            'tasks_done'=>$tasksDone,
            'nav'=> 0,
            'folder_name'=> json_encode($user->task),
            'angle'=> $angle,
            'distance'=> $distance,
            'images'=>$images
        ]);
    }
    public function folder_tasks(\App\Nav $nav){        
        $user = Auth::user();
        if($nav->user>0 && $nav->user == $user->id){
            $navs = $this->recurs(\App\Nav::where('user','=', Auth::user()->id)->where('parent_id','=',0)->orderBy('name')->get());
            $navs = json_encode($navs);

            $tasks = \App\Task::where('nav','=', $nav->id)->where('done','=', 0)->orderBy('created_at', 'asc')->get();
            $tasksDone = \App\Task::where('nav','=', $nav->id)->where('done','=', 1)->orderBy('created_at', 'asc')->get();
            $images = collect(Storage::files('public/uploads/user_'.Auth::user()->id.'/nav_'.$nav->id))->map(function($file) {
                return Storage::url($file);
            });

            $angle = Auth::user()->angle;
            $distance = Auth::user()->distance;

            return view('tasks',[
                'navs'=> $navs,
                'user'=>$user,
                'tasks'=>$tasks,
                'tasks_done'=>$tasksDone,
                'nav'=> $nav->id,
                'folder_name'=> json_encode($nav->name),
                'angle'=> $angle,
                'distance'=> $distance,
                'images'=>$images
            ]);
        }
        else{
            App::abort(404);            
        }

    }

}