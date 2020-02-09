@extends("layout")

@section("title", $file->name())

    @section("content")

	<div class="file-item">
	    <div class="name"> {{ $file->name() }} </div>
	    <div class="time"> {{ $file->updated() }} </div>
	</div>
	
	@include("tags", ["tags" => $file->tags()])

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
