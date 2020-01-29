<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

use App\Literal;
use App\File;
use App\Repository;

use App\Http\Requests\CheckFile;
use App\Http\Requests\NewFile;
use App\Http\Requests\RenameFile;


class FileController extends Controller
{
    public function show(string $filename)
    {
        $file = Repository::get($filename);
        
        if ($file === NULL) {
            abort(404);
        }
        
        return view("file")->with("file", $file);
    }

    public function showAll()
    {
        $files = Repository::all();
        return view("main")->with("files", $files);
    }
 
    public function add(NewFile $request)
    {
        $file = $request->file(Literal::nameField());
        
        Repository::save($file);
        
        return redirect()->back();
    }
    
    public function delete(CheckFile $request)
    {
        $filename = $request->input(Literal::nameField());
        Repository::delete($filename);
        
        return redirect("/");
    }
    
    public function rename(RenameFile $request)
    {
        $filename = $request->input(Literal::nameField());
        $newname = $request->input(Literal::newnameField());
        
        if ($filename !== $newname) {
            Repository::rename($filename, $newname);
        }
        
        return redirect("/".$newname);
    }
}
