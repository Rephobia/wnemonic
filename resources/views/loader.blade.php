<form action="/add" method="post" enctype="multipart/form-data">
    @csrf
    <input type="file" name="name">
    <input type="submit" value="submit">
</form>
