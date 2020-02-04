<p>
    @foreach ($tags as $tag)
	<a href={{ url("tag/".$tag) }}> {{ $tag }}</a>
    @endforeach
</p>
