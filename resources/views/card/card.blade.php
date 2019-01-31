<!-- 
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
</tr> -->
<li class="task_card" id="task_{{ $task->id }}">
	<div class="card" style="height: 100%;" >
		<img class="card-img-top" src=".../100px180/" alt="Card image cap" style="height: 30%;">
		<div class="card-body" style="height: 70%;">
			<div style="height: 80%; text-align: left;">
				<h5 class="card-title">{{ $task->name }}</h5>
				<p class="card-text">{{ $task->description }}</p>
			</div>

			<div style="height: 20%;">
				<a href="#" class="btn btn-success">Выполнено</a>
				<a href="#" class="btn btn-danger">Отменить</a>
			</div>
		</div>
	</div>
</li>