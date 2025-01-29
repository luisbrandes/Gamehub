<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$servername = "sql204.infinityfree.com";
$username = "if0_38145611";
$password = "l906DqNGC2Csj4C";
$database = "if0_38145611_userscredentials";
$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $game_id = $_POST['game_id'];
    $user_id = $_SESSION['user_id'];

    $check_sql = "SELECT * FROM user_games WHERE user_id = ? AND game_id = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param('ii', $user_id, $game_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        $insert_sql = "INSERT INTO user_games (user_id, game_id) VALUES (?, ?)";
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param('ii', $user_id, $game_id);
        if ($stmt->execute()) {
            header("Location: index.php");
        } else {
            echo "Erro ao adicionar jogo!";
        }
    } else {
        echo "Este jogo já está na sua biblioteca!";
    }
}

$conn->close();
?>