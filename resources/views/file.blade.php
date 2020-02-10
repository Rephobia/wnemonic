@extends("layout")

@section("title", $file->name())

    @section("content")

	<div class="file-item">
	    <div class="name"> {{ $file->name() }} </div>
	    <div class="time"> {{ $file->updated() }} </div>
	    
	    @php
	    $renderpath = "filerender/".$file->type();
	    @endphp

	    @if(View::exists($renderpath))
		
		<div class="render">
		    @include($renderpath, ["file" => $file])
		</div>
		
	    @else
		
		<p> Specified type of "{{ $file->type() }}" is not supported for showing </p>
		
	    @endif
	    
	    @include("tags", ["tags" => $file->tags()])

	    <div class="control">
		<a href={{ $file->link() }} >Raw</a>
		<a href={{ $file->link() }} download={{ $file->name() }}>Download</a>
		<a href={{ url("edit/".$file->name()) }}>Edit</a>
	    </div>
	</div>

    @endsection
