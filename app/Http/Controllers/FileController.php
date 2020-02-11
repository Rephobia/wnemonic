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
    
    public function file(string $filename)
    {
        $file = $this->repository->get($filename);
        
        if ($file === NULL) {
            abort(404);
        }
        
        return view("file")->with("file", $file);
    }

    public function files(int $page = 1)
    {
        $files = $this->repository->files($page);
        
        return view("main")->with("files", $files);
    }

    public function filesByTags(string $tags, int $page = 1)
    {
        $files = $this->repository->filesByTags($tags, $page);
        
        return view("main")->with("files", $files);
    }
    

    private $repository;
}
