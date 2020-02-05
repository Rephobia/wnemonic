<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Repository;
use App\Literal;
use App\Http\Requests\CheckFile;
use App\Http\Requests\RenameFile;


class EditController extends Controller
{
    public function getEditForm(string $filename)
    {
        $file = Repository::get($filename);
        
        if ($file === NULL) {
            abort(404);
        }
        
        return view("edit")->with("file", $file);
    }
    
    public function edit(RenameFile $request)
    {
        $filename = $request->input(Literal::nameField());
        $newname = $request->input(Literal::newnameField());
        
        Repository::rename($filename, $newname);
        
        return redirect("/".$newname);
    }

    public function cancel(CheckFile $request)
    {
        $filename = $request->input(Literal::nameField());
        return redirect("/".$filename);
    }

}
