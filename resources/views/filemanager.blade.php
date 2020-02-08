<a href={{ $file->link() }} ><button>Raw Link</button></a>
<a href={{ $file->link() }} download={{ $file->name() }}><button>Download</button></a>
<a href={{ url("edit/".$file->name()) }}><button>Edit</button></a>

