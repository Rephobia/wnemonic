@extends("layout")

@section("content")

    <div class="editor">
	
	<form method="post">
	    @csrf

	    <input type="hidden" name={{ \App\Literal::nameField() }} value={{ $file->name() }}>
	    <br>
	    <label>Name:</label>
	    <input type="text" name={{ \App\Literal::newnameField() }} value={{ $file->name() }}>
	    
	    <div class="control">
		<input type="text" name={{ \App\Literal::tagField() }} value={{ $file->tagsString() }}>
		<button type="reset">Reset</button>
		<button type="submit" formaction="/edit">Save</button>
		<button type="submit" formaction="/cancel">Cancel</button>
		<button class="delete" type="submit" formaction="/delete">Delete</button>
	    </div>
	    
	</form>
	
    </div>

@endsection
