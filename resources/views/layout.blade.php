<!DOCTYPE html>
<html lang="en">
    <head>
	<meta charset="UTF-8">
	<title>@yield("title", "wnemonic")</title>
    </head>
    <body>
	
	@if ($errors->any())
	    <div id="error">
		@foreach ($errors->all() as $error)
		    <li>{{ $error }}</li>
		@endforeach
	    </div>
	@endif
	
	<div id="content">
	    @yield('content')
	</div>

    </body>
</html>
