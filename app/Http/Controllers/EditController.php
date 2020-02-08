<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Repository;
use App\Literal;
use App\Http\Requests\CheckFile;
use App\Http\Requests\EditFile;
use App\Http\Requests\NewFile;


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
        session(["cancel_link" => "/".$fileName]);
        
        return view("editor/edit")->with("file", $file);
    }

    public function getAddForm()
    {
        session(["cancel_link" => url()->previous()]);
        
        return view("editor/add");
    }
    
    public function edit(EditFile $request)
    {
        $fileName = $request->input(Literal::nameField());
        $newName = $request->input(Literal::newnameField());
        $tagsString = $request->input(Literal::tagField());
        
        $file = $this->repository->get($fileName);
        $this->repository->rename($file, $newName);
        $this->repository->updateTags($file, $tagsString);
        
        return redirect("/".$newName);
    }
    
    public function add(NewFile $request)
    {
        $file = $request->file(Literal::nameField());
        $tags = $request->input(Literal::tagField());

        $fileView = $this->repository->save($file, $tags);
        
        return redirect("/".$fileView->name());
    }
    
    public function delete(CheckFile $request)
    {
        $fileName = $request->input(Literal::nameField());
        $this->repository->delete($fileName);
        
        return redirect("/");
    }
    
    public function cancel()
    {
        return redirect(session("cancel_link"));
    }

    private $repository;
}
