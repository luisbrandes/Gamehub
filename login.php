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

$error_message = ""; // Inicializar variável para mensagem de erro

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
            $error_message = "Senha incorreta!";
        }
    } else {
        $error_message = "Usuário não encontrado!";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="stylelogin.css">
    <style>
        main {
            padding: 20px;
            border-radius: 10px;

            min-width: 320px;
            max-width: 800px;
            margin: auto;
        }

        img {
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="login">
        <div class="login-container">
            <div class="logo">Bem-vindo</div>
            <h2>Entrar na conta</h2>
            <form action="login.php" method="POST">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" required>
                <label for="password">Senha:</label>
                <input type="password" name="password" id="password" required>
                <button type="submit">Entrar</button>
            </form>
            <!-- Exibe a mensagem de erro -->
            <?php if (!empty($error_message)): ?>
                <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
            <?php endif; ?>
            <p>Não tem uma conta? <a href="register.php" class="create-account">Crie uma conta</a></p>
        </div>
    </div>
</body>
</html>
