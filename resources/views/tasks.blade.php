@extends('layouts.app_tasks')
@section('content')
	<div class="card-body">
		@include('errors.errors_tasks')
	</div>
	<form action="{{ url('tasks') }}" method="post" class="form-horizontal">
		{{ csrf_field() }}
		<div class="row">
			<div class="col-sm-2">
				<div class="form-group">
				    <label for="t_date">Дата</label>
		   			<input type="date" name="date" id="t_date" class="form-control">
		   		</div>
	  		</div>
	  		<div class="col-sm-4">
				<div class="form-group">
			    	<label for="t_name">Наименование</label>
	   				<input type="text" name="name" id="t_name" class="form-control">
	   			</div>
	  		</div>
	  	</div>		
		<div class="row">			
			<div class="col-sm-6">
				<div class="form-group">
					<label for="t_description">Описание</label>
					<textarea type="textarea" name="description" id="t_description" class="form-control"></textarea>
				</div>			
			</div>
		</div>
		<div class="row">
			<div class="col-sm-2">
				<button type="submit" class="btn btn-success form-control"><i class="fa fa-plus"></i>Добавить</button>
			</div>
		</div>
	</form>
	<br>
	@if(count($tasks)>0)
		<div class="card">
			<div class="card-heading">
				Текущие задачи
			</div>
			<div class="card-body">
				<table class="table table-striped task-table">

					<thead>
						<tr>
							<th>Дата</th>
							<th>Задача</th>
							<th>Описание</th>
							<th>&nbsp</th>
						</tr>
					</thead>
					<tbody>
						@foreach($tasks as $task)
						<tr>
							<td class="table-text">
								{{ $task->date }}
							</td>
							<td class="table-text">
								{{ $task->name }}
							</td>							
							<td class="table-text">
								{{ $task->description }}
							</td>
							<td>
								<form action="{{url('tasks/'.$task->id)}}" method="post"> 
									{{ csrf_field() }}
									{{ method_field('DELETE')}}

									<button class="btn btn-danger">
										Delete
									</button>		
								</form>
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	@endif
@endsection