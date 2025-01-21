<?php
session_start();

// Configurações do banco de dados
$servername = "sql204.infinityfree.com";
$username = "if0_38145611";
$password = "l906DqNGC2Csj4C";
$database = "if0_38145611_userscredentials";

// Criar conexão com o banco de dados
$conn = new mysqli($servername, $username, $password, $database);

// Verificar conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Consultar o banco de dados
    $sql = "SELECT * FROM Users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['passwordHash'])) {
            // Login bem-sucedido
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            header("Location: inicio.php");
            exit();
        } else {
            echo "Senha incorreta!";
        }
    } else {
        echo "Usuário não encontrado!";
    }
}
?>
