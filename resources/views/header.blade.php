<a href={{ url("/") }}>Wnemonic</a>
<a href={{ url("add") }}>Add</a>

<form  method="POST" action="/search">
    @csrf
    <div class="search-bar">
	<input type="text" name={{ \App\Literal::searchField() }}>
    </div>
</form>
