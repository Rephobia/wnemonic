<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

use App\Literal;
use App\FileView;
use App\Repository;


class FileController extends Controller
{
    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }
    
    public function show(string $filename)
    {
        $file = $this->repository->get($filename);
        
        if ($file === NULL) {
            abort(404);
        }
        
        return view("file")->with("file", $file);
    }

    public function showAll()
    {
        $files = $this->repository->all();
        return view("main")->with("files", $files);
    }
         
    private $repository;
}
