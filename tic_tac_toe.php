<?php
session_start();

// Initialize the game board
if (!isset($_SESSION['board'])) {
    $_SESSION['board'] = array_fill(0, 9, '');
    $_SESSION['current_player'] = 'X';
    $_SESSION['message'] = "Player {$_SESSION['current_player']}'s turn";
}

// Handle user input
if (isset($_POST['cell'])) {
    $cell = intval($_POST['cell']);

    // Check if the cell is empty
    if ($_SESSION['board'][$cell] == '') {
        $_SESSION['board'][$cell] = $_SESSION['current_player'];

        // Check for a win or draw
        if (check_win($_SESSION['board'], $_SESSION['current_player'])) {
            $_SESSION['message'] = "Player {$_SESSION['current_player']} wins!";
            $_SESSION['game_over'] = true;
        } elseif (!in_array('', $_SESSION['board'])) {
            $_SESSION['message'] = "It's a draw!";
            $_SESSION['game_over'] = true;
        } else {
            // Switch player
            $_SESSION['current_player'] = $_SESSION['current_player'] == 'X' ? 'O' : 'X';
            $_SESSION['message'] = "Player {$_SESSION['current_player']}'s turn";
        }
    }
}

// Reset the game
if (isset($_POST['reset'])) {
    session_unset();
    header("Location: tic_tac_toe.php");
    exit();
}

// Function to check for a win
function check_win($board, $player) {
    $win_conditions = [
        [0, 1, 2],
        [3, 4, 5],
        [6, 7, 8], // Rows
        [0, 3, 6],
        [1, 4, 7],
        [2, 5, 8], // Columns
        [0, 4, 8],
        [2, 4, 6], // Diagonals
    ];

    foreach ($win_conditions as $condition) {
        if ($board[$condition[0]] == $player &&
            $board[$condition[1]] == $player &&
            $board[$condition[2]] == $player) {
            return true;
        }
    }
    return false;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tic-Tac-Toe Game</title>
    <link rel="stylesheet" href="style.css">

    <style>
        body { font-family: Arial, sans-serif; text-align: center; }
        .board { width: 150px; margin: 20px auto; display: grid; grid-template-columns: repeat(3, 50px); }
        .cell {
            width: 50px; height: 50px; border: 1px solid #000;
            display: flex; align-items: center; justify-content: center;
            font-size: 24px;
        }
        .cell button {
            width: 100%; height: 100%; font-size: 24px; background: none; border: none; cursor: pointer;
        }
        .message { font-size: 18px; margin: 10px; }
        .reset-button { margin-top: 10px; }
    </style>
</head>
<body>

<h1>Tic-Tac-Toe</h1>

<div class="message"><?php echo $_SESSION['message']; ?></div>

<form method="post">
    <div class="board">
        <?php
        for ($i = 0; $i < 9; $i++) {
            echo '<div class="cell">';
            if ($_SESSION['board'][$i] == '' && !isset($_SESSION['game_over'])) {
                echo '<button type="submit" name="cell" value="' . $i . '"></button>';
            } else {
                echo $_SESSION['board'][$i];
            }
            echo '</div>';
        }
        ?>
    </div>
    <button type="submit" name="reset" class="reset-button">Reset Game</button>
</form>

</body>
</html>
