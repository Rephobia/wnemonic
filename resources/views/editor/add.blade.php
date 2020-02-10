@extends("layout")

@section("content")

    <div class="editor">
	
	<form  method="post" enctype="multipart/form-data">
	    @csrf
	    <input class="control" type="file" name={{ \App\Literal::nameField() }}>
	    <input type="text" name={{ \App\Literal::tagField() }}>
	    
	    <div class="control">
		<button type="reset">Reset</button>
		<button type="submit" formaction="/add">Save</button>
		<button type="submit" formaction="/cancel">Cancel</button>
	    </div>
	    
	</form>
	
    </div>
    
@endsection
