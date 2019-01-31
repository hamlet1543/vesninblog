<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

	<link rel="stylesheet" type="text/css" href="/css/reg.css">
	<link rel="stylesheet" type="text/css" href="/css/card.css">
	<link rel="stylesheet" type="text/css" href="/css/nav.css">
	<link rel="stylesheet" type="text/css" href="/css/settings.css">
	<link rel="stylesheet" type="text/css" href="/css/images.css">


	<title>VesninBlog</title>
	<script src="{{ asset('js/app.js') }}" defer></script>
<!-- 	<script src="{{ asset('js/slider.js') }}" defer></script> -->

</head>
<body>


        	 	<div id="app">
		<div class="container">	
	 			
			
							
			
			@yield('content')
		</div>
	</div>






</body>
</html>
