<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repository;

class TagController extends Controller
{

    public function show(string $tags)
    {
        $files = Repository::all($tags);
        
        // $files = Repository::allByTag($tags);
        
        return view("main")->with("files", $files);
    }
        
}
