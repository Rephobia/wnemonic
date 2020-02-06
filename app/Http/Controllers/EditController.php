<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Repository;
use App\Literal;
use App\Http\Requests\CheckFile;
use App\Http\Requests\RenameFile;


class EditController extends Controller
{    
    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }
    
    public function getEditForm(string $fileName)
    {
        $file = $this->repository->get($fileName);
        
        if ($file === NULL) {
            abort(404);
        }
        
        return view("edit")->with("file", $file);
    }
    
    public function edit(RenameFile $request)
    {
        $fileName = $request->input(Literal::nameField());
        $newName = $request->input(Literal::newnameField());
        $tagsString = $request->input(Literal::tagField());
        
        $file = $this->repository->get($fileName);

        if ($fileName !== $newName) {
            $file = $this->repository->rename($file, $newName);
        }

        $this->repository->updateTags($file, $tagsString);
        
        return redirect("/".$newName);
    }

    public function cancel(CheckFile $request)
    {
        $fileName = $request->input(Literal::nameField());
        return redirect("/".$fileName);
    }

    private $repository;
}
