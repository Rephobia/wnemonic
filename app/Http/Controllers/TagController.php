<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repository;

class TagController extends Controller
{
    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }
    public function show(string $tags)
    {
        $files = $this->repository->all($tags);
                
        return view("main")->with("files", $files);
    }
    private $repository;

}
