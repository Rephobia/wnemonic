@php

if (isset($file)) {
$tagsString = $file->tagsString();
$saveAction = "/edit";

}
else {

$saveAction = "/add";
$tagsString = "";

}
@endphp

<label>Tags</label>
<input type="text" name={{ \App\Literal::tagField() }} value={{ $tagsString }}>
<br>
<button type="reset">Reset</button>
<button type="submit" formaction={{ $saveAction }}>Save</button>
<button type="submit" formaction="/cancel">Cancel</button>
