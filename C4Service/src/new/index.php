<?php
require_once '../play/Board.php';
// index.php

define('STRATEGY', 'strategy'); // constant
$strategies = array("Smart", "Random"); // supported strategies

if (!array_key_exists(STRATEGY, $_GET)) {
    echo json_encode(["response" => false, "reason" => "Strategy not in URL"]); // error message if strategy is not in URL
    exit();
}

$strategy = $_GET[STRATEGY];

// write your code here … use uniqid() to create a unique play id.
$id = uniqid(); // create unique id

if (in_array($strategy, $strategies)) { // check if strategy is in the supported strategies
    // Initialize session array if not set (stores generated pid and strategy for /play/index.php to use)
    if (!isset($_SESSION['games'])) {
        $_SESSION['games'] = [];
    }
    
    // Store strategy and a new empty board in session array
    $gameData = ['strategy' => $strategy, 'board' => serialize(new Board())];
    file_put_contents(sys_get_temp_dir() . "/c4_$id.json", json_encode($gameData));
    
    echo json_encode(["response" => true, "pid" => "$id"]); // print current strategy and id    

} else if ($strategy === "") { // check if strategy is empty
    echo json_encode(["response" => false, "reason" => "Strategy not specified"]);
} else { // print unknown strategy if strategy is not smart or random
    
    echo json_encode(["response" => false, "reason" => "Unknown Strategy"]);
}

?>