<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Auth;
use Storage;

class TaskController extends Controller
{
    public function task_add(Request $request){
    	$validator = \Validator::make($request->all(),[
            'nav',
            'path',
    		'date',
    		'name',
    		'description' 
    	]);
    	if($validator->fails()){
    		 return response(['status'=>'error', 'error'=>$validator->fails()], 200);
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
    	$task->delete();

        $tasks = \App\Task::where('nav','=', $nav)->where('done','=', '0')->orderBy('created_at', 'asc')->get();
        $tasks = json_encode($tasks);
        $tasks_done = \App\Task::where('nav','=', $nav)->where('done','=', '1')->orderBy('created_at', 'asc')->get();
        $tasks_done = json_encode($tasks_done);

        return response(['status'=>'delete', 'tasks'=>$tasks, 'tasks_done'=>$tasks_done], 200);
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

    public function task_image_delete(\App\Task $task,Request $request){
            $task->path=null;
            $task->save();

        return response(['status'=>'delete'], 200);
    }
}
