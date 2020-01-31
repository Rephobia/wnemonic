<?php

namespace App\Rules\Units;

class BasicRule
{
    public function getRedirect() : string
    {
        return $this->redirect;
    }

    public function setRedirect(string $redirect)
    {
        $this->redirect = $redirect;
    }
    
    private $redirect = "";
}
