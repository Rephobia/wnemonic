@extends("layout")

@section("content")

    <form method="post">
	@csrf

	<input type="hidden" name={{ \App\Literal::nameField() }} value={{ $file->name() }}>
	<br>
	<label>Name:</label>
	<input type="text" name={{ \App\Literal::newnameField() }} value={{ $file->name() }}>
	<br>
	@include("editor/control")
	<button type="submit" formaction="/delete" style="color:red">Delete</button>

    </form>

@endsection
