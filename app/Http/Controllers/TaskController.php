<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Auth;
use Storage;

class TaskController extends Controller
{

    public function index(){
        $angle = Auth::user()->angle;
        $distance = Auth::user()->distance;
        $tasks = \App\Task::where('nav','=', 0)->where('done','=', 0)->orderBy('created_at', 'asc')->get();
    	$tasks_done = \App\Task::where('nav','=', 0)->where('done','=', 1)->orderBy('created_at', 'asc')->get();
        $images = collect(Storage::files('public/uploads/user_'.Auth::user()->id.'/nav_0'))->map(function($file) {
            return Storage::url($file);
        });
       
    	return view('tasks',[
            'tasks'=>$tasks,
    		'tasks_done'=>$tasks_done,
            'nav'=> 0,
            'angle'=> $angle,
            'distance'=> $distance,
            'images'=>$images
        ]);
    }
    public function folder(\App\Nav $nav){
        $angle = Auth::user()->angle;
        $distance = Auth::user()->distance;
        $tasks = \App\Task::where('nav','=', $nav->id)->where('done','=', 0)->orderBy('created_at', 'asc')->get();
        $tasks_done = \App\Task::where('nav','=', $nav->id)->where('done','=', 1)->orderBy('created_at', 'asc')->get();
/*        $images = Storage::files('uploads/user_'.Auth::user()->id.'_nav_'.$nav->id);
        $images = json_encode($images);*/
        $images = collect(Storage::files('public/uploads/user_'.Auth::user()->id.'/nav_'.$nav->id))->map(function($file) {
            return Storage::url($file);
        });

        return view('tasks',[
            'tasks'=>$tasks,
            'tasks_done'=>$tasks_done,
            'nav'=>$nav,
            'angle'=> $angle,
            'distance'=> $distance,
            'images'=>$images,
        ]);
    }







    public function task_add(Request $request){
    	$validator = \Validator::make($request->all(),[
            'nav',
            'path',
    		'date',
    		'name' => 'required|max:255',
    		'description' 
    	]);
    	if($validator->fails()){
    		return redirect('/tasks')
    			->withInput()
    			->withErrors($validator);
    	}

    	$task = new \App\Task;
        $task->nav = $request->nav;
        $task->path = $request->path;
    	$task->name = $request->name;
    	$task->date = $request->date;
        $task->description = $request->description;
    	$task->done = 0;
    	$task->save();

        $tasks = \App\Task::where('nav','=', $request->nav)->where('done','=', 0)->orderBy('created_at', 'asc')->get();
        $tasks = json_encode($tasks);

    	return response(['status'=>'ok', 'tasks'=>$tasks], 200);
    }

    public function edit(\App\Task $task, Request $request){
        $validator = \Validator::make($request->all(),[
            'path',
            'date',
            'name' => 'required|max:255',
            'description' 
        ]);
        if($validator->fails()){
            return response(['status'=>'error', 'error'=>$validator->fails()], 200);
                /*->withInput()
                ->withErrors($validator);*/
        }

        $task->path = $request->path;
        $task->name = $request->name;
        $task->date = $request->date;
        $task->description = $request->description;
        $task->done = 0;
        $task->save();

        $tasks = \App\Task::where('nav','=', $task->nav)->where('done','=', 0)->orderBy('created_at', 'asc')->get();
        $tasks = json_encode($tasks);

        return response(['status'=>'done', 'tasks'=>$tasks], 200);
    }

    public function delete(\App\Task $task){
        $nav = $task->nav;
        if(isset($task->path))
           unlink(public_path($task->path));
    	$task->delete();

        $tasks = \App\Task::where('nav','=', $nav)/*->where('done','=', 'false')*/->orderBy('created_at', 'asc')->get();
        $tasks = json_encode($tasks);

        return response(['status'=>'delete', 'tasks'=>$tasks], 200);
    }
    public function done(\App\Task $task){
        $task->done = 1;
        $task->created_at = time();
        $task->save();
        $tasks = \App\Task::where('nav','=', $task->nav)->where('done','=', 0)->orderBy('created_at', 'asc')->get();
        $tasks_done = \App\Task::where('nav','=', $task->nav)->where('done','=', 1)->orderBy('created_at', 'asc')->get();
        $tasks = json_encode($tasks);
        $tasks_done = json_encode($tasks_done);

        return response(['status'=>'ok', 'tasks'=>$tasks, 'tasks_done'=>$tasks_done], 200);
    }
    public function not_done(\App\Task $task){
        $task->done = 0;
        $task->created_at = time();
        $task->save();
        $tasks = \App\Task::where('nav','=', $task->nav)->where('done','=', 0)->orderBy('created_at', 'asc')->get();
        $tasks_done = \App\Task::where('nav','=', $task->nav)->where('done','=', 1)->orderBy('created_at', 'asc')->get();
        $tasks = json_encode($tasks);
        $tasks_done = json_encode($tasks_done);

        return response(['status'=>'ok', 'tasks'=>$tasks, 'tasks_done'=>$tasks_done], 200);
    }
    public function image_upload(\App\Nav $nav, Request $request){
        $path = $request->file('image')->store('uploads/user_'.Auth::user()->id.'/nav_'.$nav->id, 'public');
        //$tasks = \App\Task::orderBy('created_at', 'asc')->get();
        $images = collect(Storage::files('public/uploads/user_'.Auth::user()->id.'/nav_'.$nav->id))->map(function($file) {
            return Storage::url($file);
        });
        return response(['images'=>$images], 200);
    }
    public function image_delete(\App\Nav $nav, Request $request){
        if(isset($request->url))
           unlink(public_path($request->url));

        $images = collect(Storage::files('public/uploads/user_'.Auth::user()->id.'/nav_'.$nav->id))->map(function($file) {
            return Storage::url($file);
        });

        return response(['images'=>$images], 200);
    }
    public function task_image_delete(\App\Task $task,Request $request){
            $task->path=null;
            $task->save();

        return response(['status'=>'delete'], 200);
    }
}
