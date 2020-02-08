@extends("layout")

@section("content")

    <form action="/add" method="post" enctype="multipart/form-data">
	@csrf
	<input type="file" name={{ \App\Literal::nameField() }}>
	<br>
	@include("editor/control")
    </form>
    
@endsection
