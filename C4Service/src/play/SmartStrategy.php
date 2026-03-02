<?php

# Import Board and MoveStrategy class files
require_once 'Board.php';

require_once 'MoveStrategy.php';

require_once 'RandomStrategy.php';

class SmartStrategy extends MoveStrategy
{

    public function pickSlot()

    {
        $current = $this->board->getCurrentTurn();

        // opponent is O if current === X, else opponent is X

        $opponent = ($current === 'X') ? 'O' : 'X';

        // Try to BLOCK

        $col = $this->checkVertical($opponent);

        if ($col !== - 1) {

            return $col;
        }

        // Try to BLOCK

        $col = $this->checkHorizontal($opponent);

        if ($col !== - 1) {

            return $col;
        }

        // Try to BLOCK

        $col = $this->checkDiagonal($opponent);

        if ($col !== - 1) {
            return $col;
        }

        // Try to WIN

        $col = $this->checkVertical($current);

        if ($col !== - 1) {

            return $col;
        }

        $col = $this->checkHorizontal($current);

        if ($col !== - 1) {

            return $col;
        }

        $col = $this->checkDiagonal($current);

        if ($col !== - 1) {

            return $col;
        }

        // No smart move → random

        $random = new RandomStrategy($this->board);

        return $random->pickSlot();
    }

    public function checkVertical($player)

    {

        // Loop through all columns
        for ($column = 0; $column < 7; $column ++) {

            // if the column is full, continue

            if ($this->board->column_is_full($column)) {

                continue;
            }

            // Check verticals

            // only check up to row 3 becasue we need to check 3,4,5,6; otherwise out of bounds

            for ($row = 5; $row >= 0; $row --) {
                $currBoard = $this->board->getBoard();
                
                // calculate row indices
                $rowMinus1 = $row-1;
                $rowMinus2 = $row-2;
                $rowMinus3 = $row-3;
                
                // adjust for wraparound if the index is out of bounds
                if($rowMinus1 < 0) {
                    $rowMinus1 += 6;
                }
                if($rowMinus2 < 0) {
                    $rowMinus2 += 6;
                }
                if($rowMinus3 < 0) {
                    $rowMinus3 += 6;
                }
                
                // WIN Vertically
                if ($currBoard[$row][$column] === $player && $currBoard[$rowMinus1][$column] === $player && $currBoard[$rowMinus2][$column] === $player) {
                    if ($currBoard[$rowMinus3][$column] === '.') {
                        return $column;
                    }
                }
            }
        }

        // no column found

        return - 1;
    }

    public function checkHorizontal($player)
    {

        // Loop through all columns
        $currBoard = $this->board->getBoard();

        for ($row = 0; $row < 6; $row ++) {

            for ($col = 0; $col < 7; $col ++) {

                // calculate column indices
                $colPlus1 = $col+1;
                $colPlus2 = $col+2;
                $colPlus3 = $col+3;
                
                // adjust for wraparound if indices are out of bounds
                if($colPlus1 > 6) {
                    $colPlus1 -= 7;
                }
                if($colPlus2 > 6) {
                    $colPlus2 -= 7;
                }
                if($colPlus3 > 6) {
                    $colPlus3 -= 7;
                }
                
                // "X X X ."
                if ($currBoard[$row][$col] === $player && $currBoard[$row][$colPlus1] === $player && $currBoard[$row][$colPlus2] === $player && $currBoard[$row][$colPlus3] === '.') {
                    return ($colPlus3);

                    // "X . X X"
                } else if ($currBoard[$row][$col] === $player && $currBoard[$row][$colPlus3] === $player && $currBoard[$row][$colPlus2] === $player && $currBoard[$row][$colPlus1] === '.') {
                    return ($colPlus1);

                    // "X X . X"
                } else if ($currBoard[$row][$col] === $player && $currBoard[$row][$colPlus1] === $player && $currBoard[$row][$colPlus3] === $player && $currBoard[$row][$colPlus2] === '.') {
                    return ($colPlus2);

                    // " . X X X"
                } else if ($currBoard[$row][$colPlus3] === $player && $currBoard[$row][$colPlus1] === $player && $currBoard[$row][$colPlus2] === $player && $currBoard[$row][$col] === '.') {
                    return ($col);
                }
            }
        }

        return - 1;
    }

    public function checkDiagonal($player)
    {

        // Loop through all columns
        $currBoard = $this->board->getBoard();

        // only rows 1 and 2, 3 valid for diagonal win

        // for bottom left to top right diagonal only columns 1- 3 valid

        for ($row = 0; $row < 3; $row ++) {

            for ($col = 0; $col < 4; $col ++) {

                if ($currBoard[$row][$col] === $player && $currBoard[$row + 1][$col + 1] === $player && $currBoard[$row + 2][$col + 2] === $player && $currBoard[$row][$col + 3] === '.') {
                    return $col + 3;
                } else if ($currBoard[$row][$col] === $player && $currBoard[$row + 3][$col + 3] === $player && $currBoard[$row + 2][$col + 2] === $player && $currBoard[$row][$col + 1] === '.') {
                    return ($col + 1);
                } else if ($currBoard[$row][$col] === $player && $currBoard[$row + 1][$col + 1] === $player && $currBoard[$row + 3][$col + 3] === $player && $currBoard[$row][$col + 2] === '.') {
                    return ($col + 2);
                } else if ($currBoard[$row + 3][$col + 3] === $player && $currBoard[$row + 1][$col + 1] === $player && $currBoard[$row + 2][$col + 2] === $player && $currBoard[$row][$col] === '.') {
                    return $col;
                }
            }
        }

        // only rows 1 and 2 valid for diagonal win

        // for bottom right to top left diagonal only columns 4- 7 valid

        for ($row = 0; $row < 3; $row ++) {

            for ($col = 3; $col < 7; $col ++) {

                if ($currBoard[$row][$col] === $player && $currBoard[$row + 1][$col - 1] === $player && $currBoard[$row + 2][$col - 2] === $player && $currBoard[$row][$col - 3] === '.') {
                    return ($col - 3);
                } else if ($currBoard[$row][$col] === $player && $currBoard[$row + 3][$col - 3] === $player && $currBoard[$row + 2][$col - 2] === $player && $currBoard[$row][$col - 1] === '.') {
                    return ($col - 1);
                } else if ($currBoard[$row][$col] === $player && $currBoard[$row + 1][$col - 1] === $player && $currBoard[$row + 3][$col - 3] === $player && $currBoard[$row][$col - 2] === '.') {
                    return ($col - 2);
                } else if ($currBoard[$row + 3][$col - 3] === $player && $currBoard[$row + 1][$col - 1] === $player && $currBoard[$row + 2][$col - 2] === $player && $currBoard[$row][$col] === '.') {
                    return $col;
                }
            }

            return - 1;
        }
    }
}

// new CODE