<!DOCTYPE html>
<html lang="en">
    <head>
	<meta charset="UTF-8">
	<title>@yield("title", "wnemonic")</title>
    </head>
    <body>
	
	<div class="error_message">
	    {{ $errors }}
	</div>
	
	@include("loader")

	<div id="content">
	    @yield('content')
	</div>

    </body>
</html>
