@extends("layout")

@section("title", $file->name())

    @section("content")
	<p> {{ $file->name() }} </p>
	
	@php
	$renderpath = "filerender/".$file->type();
	@endphp

	@if(View::exists($renderpath))
	    @include($renderpath, ["file" => $file])
	@else
	    <p> Specified type of "{{ $file->type() }}" is not supported for showing </p>
	@endif


	@include("filemanager", ["file" => $file])


	
    @endsection
