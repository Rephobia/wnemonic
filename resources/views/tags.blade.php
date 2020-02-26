<div class="tags">
    
    @foreach ($tags as $tag)
	
	<a class="tag" href={{ url("search/".$tag) }}> {{ $tag }}</a>
	
    @endforeach
    
</div>
