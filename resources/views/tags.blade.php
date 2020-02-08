<p>
    @foreach ($tags as $tag)
	<a class="tag" href={{ url("tag/".$tag) }}> {{ $tag }}</a>
    @endforeach
</p>
