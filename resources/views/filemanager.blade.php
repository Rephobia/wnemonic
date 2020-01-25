<a href={{ $file->link() }} ><button>Raw Link</button></a>
<a href={{ $file->link() }} download={{ $file->name() }}><button>Download</button></a>

<form method="post">
    @csrf
    <input type="hidden" name="name" value={{ $file->name() }}>
    <button type="submit" formaction="/delete">Delete</button>
    <button type="submit" formaction="/rename">Rename</button>
    <input type="text" name="newname" value={{ $file->name() }}>
</form>
