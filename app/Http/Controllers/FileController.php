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
use App\Rules\UniqueFile;

class FileController extends Controller
{
    // /*
    // |--------------------------------------------------------------------------
    // | Confirm Password Controller
    // |--------------------------------------------------------------------------
    // |
    // | This controller is responsible for handling password confirmations and
    // | uses a simple trait to include the behavior. You're free to explore
    // | this trait and override any functions that require customization.
    // |
    // */

    public function show(string $filename)
    {
        $file = File::get($filename);
        
        if ($file === NULL) {
            abort(404);
        }
        
        return view("file")->with("file", $file);
    }

    public function show_all()
    {
        $files = File::all();
        // echo $files;
        return view("main")->with("files", $files);
        // return \View::make("main")->with("files", $files);
    }
 
    public function add(Request $request)
    {
        $rules = ["name" => new UniqueFile];
        $this->validate($request, $rules);

        if ($request->hasFile("name")) {
            $fileform = $request->file("name");

            $file = File::fromForm($fileform);
        }
        
        return $this->show_all();
    }
}
