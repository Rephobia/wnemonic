<a href={{ url("/") }}>Wnemonic</a>
<a href={{ url("add") }}>Add</a>

<form  method="POST" action="/search">
    @csrf
    <input id="search-bar" type="text" name={{ \App\Literal::searchField() }} value="{{ $tags ?? ""}}">
</form>
