<form action="/add" method="post" enctype="multipart/form-data">
    @csrf
    <input type="file" name={{ \App\Literal::nameField() }}>
    <input type="submit" value="submit">
</form>
