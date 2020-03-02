<!DOCTYPE html>
<html lang="en">
    <head>
	<meta charset="UTF-8">
	<title>@yield("title", env("APP_NAME"))</title>
	<link rel="stylesheet" type="text/css" href={{ url("css/style.css") }}>
    </head>
    <body>
	<header>
	    @include("header")
	</header>
	
	@if ($errors->any())
	    <div id="error">
		@foreach ($errors->all() as $error)
		    <li>{{ $error }}</li>
		@endforeach
	    </div>
	@endif
	
	<div id="content">
	    @yield("content")
	</div>

    </body>
</html>
