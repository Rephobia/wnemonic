@extends("layout")

@section("content")

    <form method="post">
	@csrf

	<input type="hidden" name={{ \App\Literal::nameField() }} value={{ $file->name() }}>
	<br>
	<label>Name:</label>
	<input type="text" name={{ \App\Literal::newnameField() }} value={{ $file->name() }}>
	<br>
	<label>Tags</label>
	<input type="text" name={{ \App\Literal::tagField() }} value={{ $file->tagsString() }}>
	<br>
	<button type="reset">Reset</button>
	<button type="submit" formaction="/edit">Save</button>
	<button type="submit" formaction="/cancel">Cancel</button>
	<button type="submit" formaction="/delete" style="color:red">Delete</button>
    </form>

@endsection
