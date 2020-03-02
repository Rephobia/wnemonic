<div class="tags">
    
    @foreach ($file->tags() as $tag)
	
	@if ($tag !== $file->name()) 
	    <a class="tag" href={{ url("search/".$tag) }}> {{ $tag }}</a>
	@endif
	
    @endforeach
    
</div>
