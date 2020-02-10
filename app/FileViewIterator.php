<?php

namespace App;

class FileViewIterator implements \Iterator
{
    public function __construct($paginator)
    {
        $this->paginator = $paginator;
        $this->position = 0;
        $this->return  = $this->createLinks();
    }
    public function pages() : string
    {
        return $this->return ;
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

    private function createLinks() : string
    {
        $links = $this->paginator->links();
        
        $paramPattern = "#\?page=#";
        $paramReplace = "/page/";
        $transition = preg_replace($paramPattern, $paramReplace, $links);

        $argumPattern = "#page/([1-9]+[0-9]*)/page/([1-9]+[0-9]*)#";
        $argumReplace = "page/$2";
        
        return preg_replace($argumPattern, $argumReplace, $transition);
                
    }
    
    private $paginator;
    private int $position = 0;
    private string $links;
}
