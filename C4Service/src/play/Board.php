<?php
# Board class
class Board {
    # Attributes
    private $numRows = 6;
    private $numColumns = 7;
    private $board = array();
    private $currentTurn;
    
    # Constructor that fills the board with empty values (represented by '.')
    public function __construct() {
        for($i = 0; $i < $this->numRows; $i++) {
            $currRow = array();
            
            for($j = 0; $j < $this->numColumns; $j++) {
                    $currRow[] = '.';
            }
                
            $this->board[] = $currRow;
        }
        
        $this->currentTurn = 'X'; // default first turn to the user
    }
    
    # Gets the number of rows in the board
    public function getNumRows() {
        return $this->numRows;
    }
    
    # Gets the number of columns in the board
    public function getNumColumns() {
        return $this->numColumns;
    }
    
    # Gets the current board
    public function getBoard() {
        return $this->board;
    }
    
    # Gets current turn
    public function getCurrentTurn() {
        return $this->currentTurn;
    }
    
    # Function that prints the contents of the board
    public function showBoard() {
        echo "Current Board: <br/>";
        for($i = 0; $i < $this->numRows; $i++) {
            
            for($j = 0; $j < $this->numColumns; $j++) {
                $stringValue = (string)$this->board[$i][$j];
                
                if($stringValue === '.') {
                    echo "$stringValue   ";
                } else {
                    echo "$stringValue  ";
                    
                }
            }
            echo "<br/>";
        }
    }
    
    # Function that places the piece given the column number and switches the turn
    # Note - This function is used only for simulation purposes
    public function place(int $columnNumber) {
        $currRow = $this->numRows - 1;
        
        while($this->board[$currRow][$columnNumber] !== '.') {
            $currRow--;
        }
        
        if($this->currentTurn === 'X') {
            $this->board[$currRow][$columnNumber] = 'X';
        } else {
            $this->board[$currRow][$columnNumber] = 'O';
        }
        
        // check if this move is a winning move
        $this->switchTurn($this->currentTurn);
    }
    
    # Function that switches the turn of the game
    public function switchTurn($currentTurn) {
        if($currentTurn === 'X') {
            $this->currentTurn = 'O';
        } else {
            $this->currentTurn = 'X';
        }
    }
    
    # Function that determines if the board is full
    public function boardIsFull() {
        for($i = 0; $i < $this->numRows; $i++) {
            
            for($j = 0; $j < $this->numColumns; $j++) {
                if($this->board[$i][$j] == '.') {
                    return false;
                }
            }
        }
        
        return true;
    }
    
    # Function that determines whether a column is full
    public function column_is_full(int $columnNumber) {
        for($i = $this->numRows - 1; $i >= 0; $i--) {
            if($this->board[$i][$columnNumber] === '.') {
                return false;
            }
        }
        
        return true;
    }
}
?>