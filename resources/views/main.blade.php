@extends("layout")

@section("content")

    @foreach($files as $file)
	
	<li class="file-item">
	    
	    <div class="oneline">
		<a class="name" href={{ url($file->name()) }}> {{ $file->name() }} </a>
		<span class="time"> {{ $file->updated()}} </span>
	    </div>
	    
	    @include("tags", array("file" => $file))
	    
	</li>
	
    @endforeach
    
    {!! $files->pages() !!}
    
@endsection
