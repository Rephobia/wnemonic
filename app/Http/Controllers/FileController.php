<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
// use App\Providers\RouteServiceProvider;
// use Illuminate\Foundation\Auth\ConfirmsPasswords;
// use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

use App\File;
use App\Http\Requests\CheckName;
use App\Http\Requests\NewFile;
use App\Http\Requests\RenameFile;


class FileController extends Controller
{
    public function show(string $filename)
    {
        $file = File::get($filename);
        if ($file === NULL) {
            abort(404);
        }
        
        return view("file")->with("file", $file);
    }

    public function showAll()
    {
        $files = File::all();
        return view("main")->with("files", $files);
    }
 
    public function add(NewFile $request)
    {
        $fileform = $request->file("name");
        $file = File::fromForm($fileform);
        
        return redirect()->back();
    }
    
    public function delete(CheckName $request)
    {
        $filename = $request->input("name");
        \App\FileStorage::delete($filename);
        
        return redirect("/");
    }
    
    public function rename(RenameFile $request)
    {
        $filename = $request->input("name");
        $newname = $request->input("newname");
        
        if ($filename !== $newname) {
            \App\FileStorage::rename($filename, $newname);
        }
        
        return redirect("/".$newname);
    }
}
