<?php
require_once 'Board.php';
require_once 'MoveStrategy.php';
require_once 'RandomStrategy.php';
require_once 'SmartStrategy.php';

class Game
{

    private string $pid;

    private Board $board;

    private MoveStrategy $strategy;

    private bool $isGameOver;

    public function __construct(string $pidIn, Board $boardIn, string $strategyIn)
    { // constructor
        $this->pid = $pidIn;
        $this->board = $boardIn;

        if ($strategyIn === "Random") {
            $this->strategy = new RandomStrategy($this->board);
        } else if ($strategyIn === "Smart") {
            $this->strategy = new SmartStrategy($this->board);
        }

        $this->isGameOver = false;
    }

    public function getPid()
    {
        return $this->pid;
    }

    public function getBoard()
    {
        return $this->board;
    }

    public function getStrategy()
    {
        return $this->strategy;
    }

    public function getIsGameOver()
    {
        return $this->isGameOver;
    }

    // Function that executes a single set of turns by the player and the computer
    public function makeMove(int $column)
    {
        // initialize parameters for json messages
        $playerIsWin = false;
        $playerIsDraw = false;
        $playerWinningRow = [];

        $computerIsWin = false;
        $computerIsDraw = false;
        $computerWinningRow = [];

        $computerColumn = 0;

        // check if player selected column is not full; if so, just return the json messages
        if (! ($this->board->column_is_full($column))) {

            // player makes move
            $this->board->place($column);

            // check if player move is a winning move; if so, change playerIsWin to true and find the playerWinningRow
            $playerWinningRow = $this->checkWin('X');
            if (! (empty($playerWinningRow))) {
                $playerIsWin = true;
                $this->isGameOver = true;
                return [
                    [
                        "slot" => $column,
                        "isWin" => $playerIsWin,
                        "isDraw" => $playerIsDraw,
                        "row" => $playerWinningRow
                    ]
                ];
            }

            // check if player move results in a draw
            if ((empty($playerWinningRow)) && $this->board->boardIsFull()) {
                $playerIsDraw = true;
                $this->isGameOver = true;
                return [
                    [
                        "slot" => $column,
                        "isWin" => $playerIsWin,
                        "isDraw" => $playerIsDraw,
                        "row" => $playerWinningRow
                    ]
                ];
            }

            // if above cases fail, computer makes move
            $computerColumn = $this->strategy->pickSlot();
            $this->board->place($computerColumn);

            // check if computer move is a winning move; if so, change computerIsWin to true and find the computerWinningRow
            $computerWinningRow = $this->checkWin('O');
            if (! (empty($computerWinningRow))) {
                $computerIsWin = true;
                $this->isGameOver = true;
                return [
                    [
                        "slot" => $column,
                        "isWin" => $playerIsWin,
                        "isDraw" => $playerIsDraw,
                        "row" => $playerWinningRow
                    ],
                    [
                        "slot" => $computerColumn,
                        "isWin" => $computerIsWin,
                        "isDraw" => $computerIsDraw,
                        "row" => $computerWinningRow
                    ]
                ];
            }

            // check if computer move results in a draw
            if ((empty($computerWinningRow)) && $this->board->boardIsFull()) {
                $computerIsDraw = true;
                $this->isGameOver = true;
                return [
                    [
                        "slot" => $column,
                        "isWin" => $playerIsWin,
                        "isDraw" => $playerIsDraw,
                        "row" => $playerWinningRow
                    ],
                    [
                        "slot" => $computerColumn,
                        "isWin" => $computerIsWin,
                        "isDraw" => $computerIsDraw,
                        "row" => $computerWinningRow
                    ]
                ];
            }
        }

        return [
            [
                "slot" => $column,
                "isWin" => $playerIsWin,
                "isDraw" => $playerIsDraw,
                "row" => $playerWinningRow
            ],
            [
                "slot" => $computerColumn,
                "isWin" => $computerIsWin,
                "isDraw" => $computerIsDraw,
                "row" => $computerWinningRow
            ]
        ];
    }

    // Function that checks if a player has a winning combo; if so
    public function checkWin($player)
    {
        $currBoard = $this->board->getBoard();

        // check horizontal

        // Loop through all columns
        for ($row = 0; $row < 6; $row ++) {

            for ($col = 0; $col < 7; $col ++) {

                // calculate column indices
                $colPlus1 = $col + 1;
                $colPlus2 = $col + 2;
                $colPlus3 = $col + 3;

                // adjust for wraparound if indices are out of bounds
                if ($colPlus1 > 6) {
                    $colPlus1 -= 7;
                }
                if ($colPlus2 > 6) {
                    $colPlus2 -= 7;
                }
                if ($colPlus3 > 6) {
                    $colPlus3 -= 7;
                }

                // "X X X ."
                if ($currBoard[$row][$col] === $player && $currBoard[$row][$colPlus1] === $player && $currBoard[$row][$colPlus2] === $player && $currBoard[$row][$colPlus3] === $player) {
                    return [
                        
                        $col,
                        $row,
                        $colPlus1,
                        $row,
                        $colPlus2,
                        $row,
                        $colPlus3,
                        $row
                        
                    ];

                    // "X . X X"
                } else if ($currBoard[$row][$col] === $player && $currBoard[$row][$colPlus3] === $player && $currBoard[$row][$colPlus2] === $player && $currBoard[$row][$colPlus1] === $player) {
                    return [
                        $col,
                        $row,
                        $colPlus1,
                        $row,
                        $colPlus2,
                        $row,
                        $colPlus3,
                        $row
                    ];

                    // "X X . X"
                } else if ($currBoard[$row][$col] === $player && $currBoard[$row][$colPlus1] === $player && $currBoard[$row][$colPlus3] === $player && $currBoard[$row][$colPlus2] === $player) {
                    return [
                        
                        $col,
                        $row,
                        $colPlus1,
                        $row,
                        $colPlus2,
                        $row,
                        $colPlus3,
                        $row
                    ];

                    // " . X X X"
                } else if ($currBoard[$row][$colPlus3] === $player && $currBoard[$row][$colPlus1] === $player && $currBoard[$row][$colPlus2] === $player && $currBoard[$row][$col] === $player) {
                    return [
                        
                        $col,
                        $row,
                        $colPlus1,
                        $row,
                        $colPlus2,
                        $row,
                        $colPlus3,
                        $row
                    ];
                }
            }
        }

        // Check verticals

        // Loop through all columns
        for ($column = 0; $column < 7; $column ++) {

            // only check up to row 3 becasue we need to check 3,4,5,6; otherwise out of bounds

            for ($row = 5; $row >= 0; $row --) {
                $currBoard = $this->board->getBoard();

                // calculate row indices
                $rowMinus1 = $row - 1;
                $rowMinus2 = $row - 2;
                $rowMinus3 = $row - 3;

                // adjust for wraparound if indices are out of bounds
                if ($rowMinus1 < 0) {
                    $rowMinus1 += 6;
                }
                if ($rowMinus2 < 0) {
                    $rowMinus2 += 6;
                }
                if ($rowMinus3 < 0) {
                    $rowMinus3 += 6;
                }

                // WIN Vertically
                if ($currBoard[$row][$column] === $player && $currBoard[$rowMinus1][$column] === $player && $currBoard[$rowMinus2][$column] === $player) {
                    if ($currBoard[$rowMinus3][$column] === $player) {
                        return [                            
                            $column,
                            $row,
                            $column,
                            $rowMinus1,
                            $column,
                            $rowMinus2,
                            $column,
                            $rowMinus3
                        ];
                    }
                }
            }

            // check diagonal

            // only rows 1 and 2, 3 valid for diagonal win

            // for bottom left to top right diagonal only columns 1- 3 valid

            for ($row = 0; $row < 3; $row ++) {

                for ($col = 0; $col < 4; $col ++) {

                    // calculate row and columns, and adjust for out of bounds indices
                    $rowPlus1 = $row + 1;
                    $rowPlus2 = $row + 2;
                    $rowPlus3 = $row + 3;
                    
                    if($rowPlus1 > 5) {
                        $rowPlus1 -= 6;
                    }
                    if($rowPlus2 > 5) {
                        $rowPlus2 -= 6;
                    }
                    if($rowPlus3 > 5) {
                        $rowPlus3 -= 6;
                    }
                    
                    $columnPlus1 = $col + 1;
                    $columnPlus2 = $col + 2;
                    $columnPlus3 = $col + 3;
                    
                    if($columnPlus1 > 6) {
                        $columnPlus1 -= 7;
                    }
                    if($columnPlus2 > 6) {
                        $columnPlus2 -= 7;
                    }
                    if($columnPlus3 > 6) {
                        $columnPlus3 -= 7;
                    }
                    
                    if ($currBoard[$row][$col] === $player && $currBoard[$rowPlus1][$columnPlus1] === $player && $currBoard[$rowPlus2][$columnPlus2] === $player && $currBoard[$rowPlus3][$columnPlus3] === $player) {

                        return [
                            $col,
                            $row,
                            $columnPlus1,
                            $rowPlus1,
                            $columnPlus2,
                            $rowPlus2,
                            $columnPlus3,
                            $rowPlus3
                        ];
                    }
                }
            }

            // only rows 1 and 2 valid for diagonal win

            // for bottom right to top left diagonal only columns 4- 7 valid

            for ($row = 0; $row < 3; $row ++) {

                for ($col = 3; $col < 7; $col ++) {

                    // calculate row and columns, and adjust for out of bounds indices
                    $rowPlus1 = $row + 1;
                    $rowPlus2 = $row + 2;
                    $rowPlus3 = $row + 3;
                    
                    if($rowPlus1 > 5) {
                        $rowPlus1 -= 6;
                    }
                    if($rowPlus2 > 5) {
                        $rowPlus2 -= 6;
                    }
                    if($rowPlus3 > 5) {
                        $rowPlus3 -= 6;
                    }
                    
                    $columnMinus1 = $col - 1;
                    $columnMinus2 = $col - 2;
                    $columnMinus3 = $col - 3;
                    
                    if($columnMinus1 > 6) {
                        $columnMinus1 -= 7;
                    }
                    if($columnMinus2 > 6) {
                        $columnMinus2 -= 7;
                    }
                    if($columnMinus3 > 6) {
                        $columnMinus3 -= 7;
                    }
                    
                    if ($currBoard[$row][$col] === $player && $currBoard[$rowPlus1][$columnMinus1] === $player && $currBoard[$rowPlus2][$columnMinus2] === $player && $currBoard[$rowPlus3][$columnMinus3] === $player) {

                        return [                 
                            $col,
                            $row,
                            $columnMinus1,
                            $rowPlus1,
                            $columnMinus2,
                            $rowPlus2,
                            $columnMinus3,
                            $rowPlus3
                            
                        ];
                    }
                }
            }
        }
        
        return [];
    }
}
?>