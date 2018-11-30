<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class TaskController extends Controller
{
    public function index(){
    	$tasks = \App\Task::orderBy('created_at', 'asc')->get();
    	return view('tasks',[
    		'tasks'=>$tasks
    	]);
    }
    public function request(Request $request){
    	$validator = \Validator::make($request->all(),[
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
    	$task->name = $request->name;
    	$task->date = $request->date;
    	$task->description = $request->description;
    	$task->save();

    	return redirect('/tasks');
    }
    public function delete(\App\Task $task){
    	$task->delete();
    	return redirect('/tasks');
    }
}
