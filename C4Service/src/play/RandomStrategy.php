<?php
# Import Board and MoveStrategy class files
require_once 'Board.php';
require_once 'MoveStrategy.php';

class RandomStrategy extends MoveStrategy{
    
    public function pickSlot(){
        # check if the board is full; if so, return -1
        if($this->board->boardIsFull()) {
            return -1;
        }
        
        # assign a random column number to be checked
        $randomNumber = mt_rand(0, 6);
 
        while($this->board->column_is_full($randomNumber)){
            #while the column at random number is full, check again til it is not
            $randomNumber = mt_rand(0,6);  
        }    
        
        return $randomNumber; // return column number
    }
    
}
?>