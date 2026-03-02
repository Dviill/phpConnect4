<?php

require_once 'Game.php';
require_once 'Board.php';

// Get pid and column parameters from URL
$pid = $_GET['pid'] ?? null;
$move = $_GET['move'] ?? null;

$gameFile = sys_get_temp_dir() . "/c4_$pid.json";

if($pid === null) { // check if the pid is not provided
    echo json_encode(["response" => false, "reason" => "Pid not specified"]);
} else if($move === null) { // check if the move is not provided
    echo json_encode(["response" => false, "reason" => "Move not specified"]);
} else if(!file_exists($gameFile)) { // check if the pid exists in the session array
    echo json_encode(["response" => false, "reason" => "Unknown pid"]);
} else if($move < 0 || $move > 6) { // check if the move is within the range of columns (0-6)
    echo json_encode(["response" => false, "reason" => "Invalid slot, $move"]);
} else {
    $gameData = json_decode(file_get_contents($gameFile), true);
    
    $strategyString = $gameData['strategy']; // get the strategy from the corresponding pid in the session array
    $currentBoard = unserialize($gameData['board']);  // get the last state of the board from the corresponding pid in the session array
        
    // TODO: create and simulate a set of turns, and delete the pid from the session array after it is completed
    
    // Create a new game object with the pid, board, and strategy from the session array and simulate a set of moves using the move parameter
    $game = new Game($pid, $currentBoard, $strategyString);
    
    // Check if the selected column is full; if so, notify user and exit
    if($game->getBoard()->column_is_full($move) && (!($game->checkWin('X')) || !($game->checkWin('O')))) {
        echo json_encode(["response" => false, "reason" => "column is full"]);
        exit;
    }
    
    $moveMessages = $game->makeMove($move); // simulate a set of moves
    
    
    // save the board to the session array
    $gameData['board'] = serialize($game->getBoard());
    file_put_contents($gameFile, json_encode($gameData));
        
    if(count($moveMessages) == 1) { // Check if player won; if so, print player win message
        $playerMoveMessage = $moveMessages[0];
        echo json_encode(["response" => true, "ack_move" => $playerMoveMessage]);
    } else {
        // get player and computer json messages
        $playerMoveMessage = $moveMessages[0];
        $computerMoveMessage = $moveMessages[1];
        
        // display the formatted json message
        echo json_encode(["response" => true, "ack_move" => $playerMoveMessage, "move" => $computerMoveMessage]);
    }
    
    if($game->getIsGameOver()) { // If the turn resulted in a game or draw, delete the pid from the session array
        unlink($gameFile);
    }
}
?>