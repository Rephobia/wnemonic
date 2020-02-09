@extends("layout")

@section("title", "wnemonic")

@section("content")

    @foreach($files as $file)
	
	<li class="main">
	    
	    <div class="oneline">
		<a class="name" href={{ url($file->name()) }}> {{ $file->name() }} </a>
		<span class="time"> {{ $file->updated()}} </span>
	    </div>
	    
	    @include("tags", ["tags" => $file->tags()])
	    
	</li>
	
    @endforeach
    
@endsection
