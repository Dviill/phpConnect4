<?php

abstract class MoveStrategy
{
    
    var $board;
    
    function __construct(Board $board = null)
    {
        $this->board = $board;
    }
    
    abstract function pickSlot();
    
    function toJson()
    {
        return array(
            ‘name’ => get_class($this)
        );
    }
    
    static function fromJson()
    {
        $strategy = new static();
        return $strategy;
    }
}
?>