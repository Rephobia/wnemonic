<?php

namespace App;

class FileViewIterator implements \Iterator
{
    public function __construct($paginator)
    {
        $this->paginator = $paginator;
        $this->position = 0;
    }
    public function renderPages()
    {
        return $this->paginator->links();
    }
    
    public function rewind()
    {
        $this->position = 0;
    }

    public function current()
    {
        return new FileView ($this->paginator[$this->position]);
    }
    
    public function key()
    {
        return $this->position;
    }
    public function next()
    {
        ++$this->position;
    }
    
    public function valid()
    {
        return isset($this->paginator[$this->position]);
    }
    
    private $paginator;
    private $position = 0;
}
