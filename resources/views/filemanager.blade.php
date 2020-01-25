<a href={{ $file->link() }} ><button>Raw Link</button></a>
<a href={{ $file->link() }} download={{ $file->name() }}><button>Download</button></a>
<form method="POST">
    <button>Delete</button>
    <button>Rename</button>
</form>
