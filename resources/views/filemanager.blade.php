<a href={{ $file->link() }} ><button>Raw Link</button></a>
<a href={{ $file->link() }} download={{ $file->name() }}><button>Download</button></a>

<form method="post">
    @csrf
    <input type="hidden" name={{ \App\Literal::nameField() }} value={{ $file->name() }}>
    <input type="text" name={{ \App\Literal::newnameField() }} value={{ $file->name() }}>
    <button type="submit" formaction="/rename">Rename</button>
    <button type="submit" formaction="/delete">Delete</button>
</form>

