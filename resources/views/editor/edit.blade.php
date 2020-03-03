@extends("layout")

@section("content")


    @php
    $fileName = $file->name();
    
    $tags= implode(",", array_filter($file->tags(), function($tag) use ($fileName)
                                                    {
                                                        return $tag !== $fileName;
                                                    }));
    @endphp
    <div class="editor">
	
	<form method="post">
	    @csrf
	    
	    <input type="hidden" name={{ \App\Literal::nameField() }} value={{ $fileName }}>
	    
	    <div class="named-input">
		<label>Name:</label>
		<input type="text" name={{ \App\Literal::newnameField() }} value={{ $fileName }}>
	    </div>
	    
	    <div class="named-input">
		<label>Tags:</label>
		<input type="text" name={{ \App\Literal::tagsField() }} value={{ $tags }}>
	    </div>
	    
	    <div class="named-input">
		<label>Pass:</label>
		<input type="password" name={{ \App\Literal::passField() }}>
	    </div>
	    
	    <div class="control">
		<button type="reset">Reset</button>
		<button type="submit" formaction="/edit">Save</button>
		<a href={{ $cancelLink }}>Cancel</a>
		<button class="delete" type="submit" formaction="/delete">Delete</button>
	    </div>
	    
	</form>
	
    </div>

@endsection
