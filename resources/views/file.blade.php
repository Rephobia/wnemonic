@extends("layout")

@section("title", $file->name())

    @section("content")
	
	<p> {{ $file->name() }} </p>
    
@endsection
