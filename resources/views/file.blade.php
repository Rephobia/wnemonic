@extends("layout")

@section("title", $file->name())

    @section("content")
	<p> {{ $file->name() }} </p>
	@includeif("filerender/".$file->type(),	["file" => $file])
	
    @endsection
