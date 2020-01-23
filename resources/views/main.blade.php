@extends("layout")

@section("title", "wnemonic")

@section("content")
	
    @foreach($files as $file)
	<li>
	    <a href="{{ url($file->name()) }}"> {{ $file->name() }} </a>
	</li>
    @endforeach
    
@endsection
