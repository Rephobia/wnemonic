<form method="POST">
    @csrf
    <button type="submit" formaction={{ $file->link() }}>Raw link</button>
    <button>Download</button>
    <button>Delete</button>
    <button>Rename</button>
</form>
