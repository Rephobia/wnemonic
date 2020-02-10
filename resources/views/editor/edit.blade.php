@extends("layout")

@section("content")

    <div class="editor">
	
	<form method="post">
	    @csrf

	    <input type="hidden" name={{ \App\Literal::nameField() }} value={{ $file->name() }}>
	    
	    <div class="named-input">
		<label>Name:</label>
		<input type="text" name={{ \App\Literal::newnameField() }} value={{ $file->name() }}>
	    </div>
	    
	    <div class="named-input">
		<label>Tags:</label>
		<input type="text" name={{ \App\Literal::tagField() }} value={{ $file->tagsString() }}>
	    </div>
	    
	    <div class="control">
		<button type="reset">Reset</button>
		<button type="submit" formaction="/edit">Save</button>
		<button type="submit" formaction="/cancel">Cancel</button>
		<button class="delete" type="submit" formaction="/delete">Delete</button>
	    </div>
	    
	</form>
	
    </div>

@endsection
