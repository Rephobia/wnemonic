@extends("layout")

@section("content")

    <form method="post">
	@csrf
	<input type="hidden" name={{ \App\Literal::nameField() }} value={{ $file->name() }}>
	<label>name</label>
	<input type="text" name={{ \App\Literal::newnameField() }} value={{ $file->name() }}>
	<br>
	<label>tags</label>
	<input type="text" name={{ \App\Literal::tagField() }} value={{ $file->tagsString() }}>
	<br>
	<button type="submit" formaction="/edit">Save</button>
	<button type="submit" formaction="/cancel">Cancel</button>
    </form>

@endsection
