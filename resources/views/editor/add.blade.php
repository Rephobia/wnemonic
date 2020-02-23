@extends("layout")

@section("content")

    <div class="editor">
	
	<form  method="post" enctype="multipart/form-data">
	    @csrf
	    <div class="upload">
		<input type="file" name={{ \App\Literal::fileField()}}>
	    </div>

	    <div class="named-input">
		<label>Tags:</label>
		<input type="text" name={{ \App\Literal::tagsField() }}>
	    </div>
	    
	    <div class="named-input">
		<label>Pass:</label>
		<input type="password" name={{ \App\Literal::passField() }}>
	    </div>
	    
	    <div class="control">
		<button type="reset">Reset</button>
		<button type="submit" formaction="/add">Save</button>
		<button type="submit" formaction="/cancel">Cancel</button>
	    </div>
	    
	</form>
    </div>
    
@endsection
