@extends('layouts.app_tasks')
@section('content')

	{{ csrf_field() }}
	@if (Route::has('login'))
		@auth
			<nav-component :user="{{ $user }}" :navs="{{ $navs }}" ></nav-component>
			<list-cards :tasks_load="{{ $tasks }}" :tasks_done_load="{{ $tasks_done }}" :images_load="{{ $images }}" :nav="{{ $nav }}" :folder_name="{{ $folder_name }}":angle_load="{{ $angle }}" :distance_load="{{ $distance }}" ></list-cards>
		@else
		<div id="reg" v-if="!reg">
			<form method="POST" action="{{ route('login') }}">
				@csrf
				<label for="name">E-mail:</label>
				<input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>
				@if ($errors->has('email'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
				<label for="username">Пароль:</label>
				<!-- <p><a href="#">Забыли пароль?</a></p> -->
				<input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>
				@if ($errors->has('password'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
				<div id="lower">
					<!-- <input type="checkbox"><label class="check" for="checkbox">Запомнить меня</label> -->

					<input type="submit" value="Войти">
					<a href="#" @click="reg = true">Регистрация</a>
				</div>
			</form>
			</div>

			<div id="reg" class="reg" v-if="reg">
			<form method="POST" action="{{ route('register') }}">
				@csrf

				<label for="name">Login:</label>
				<input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus>
                @if ($errors->has('name'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                @endif
				<label for="name">E-mail:</label>
				<input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>
                @if ($errors->has('email'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
				<label for="username">Пароль:</label>
				<input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>
                @if ($errors->has('password'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
                <label for="username">Повторите пароль:</label>
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>

				<div id="lower">
					<!-- <input type="checkbox"><label class="check" for="checkbox">Запомнить меня</label> -->

					<input type="submit" value="Зарегистрировать">
					<a href="#" @click="reg = false">Форма входа</a>
				</div>
			</form>
		</div>        

	    @endauth
	@endif

@endsection