<?php
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

$message = ""; // Inicializar variável para mensagens
$name = $email = ""; // Inicializar campos para preservar valores em caso de erro
$successMessage = ""; // Para a mensagem de sucesso

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Verificar se o email já existe
    $sql = "SELECT * FROM Users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Se o email já existe, mostrar a mensagem abaixo do botão
        $message = "Este email já está registrado!";
    } else {
        // Criptografar a senha
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);

        // Inserir o novo usuário no banco de dados
        $sql = "INSERT INTO Users (name, email, passwordHash) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sss', $name, $email, $passwordHash);

        if ($stmt->execute()) {
            // Mensagem de sucesso, fica visível no centro da tela
            $successMessage = "Conta criada com sucesso! Agora você pode <a href='login.php'>entrar</a>.";
            $name = $email = ""; // Limpar campos após sucesso
        } else {
            $message = "Erro ao criar a conta: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Conta</title>
    <link rel="stylesheet" href="styleregister.css">
</head>
<body>
    <!-- Se a mensagem de sucesso existir, exibe ela no centro da tela -->
    <?php if (!empty($successMessage)): ?>
        <div class="success-message-container">
            <div class="success-message">
                <p><?php echo $successMessage; ?></p>
            </div>
        </div>
    <?php else: ?>
        <!-- Se não for a mensagem de sucesso, exibe o formulário -->
        <div class="register">
            <div class="register-container">
                <div class="logo">Bem-vindo</div>
                <h2>Criar uma nova conta</h2>

                <!-- Formulário -->
                <form action="register.php" method="POST">
                    <label for="name">Nome:</label>
                    <input 
                        type="text" 
                        name="name" 
                        id="name" 
                        value="<?php echo htmlspecialchars($name); ?>" 
                        required>
                    <label for="email">Email:</label>
                    <input 
                        type="email" 
                        name="email" 
                        id="email" 
                        value="<?php echo htmlspecialchars($email); ?>" 
                        required>
                    <label for="password">Senha:</label>
                    <input type="password" name="password" id="password" required>
                    <button type="submit">Criar Conta</button>
                </form>

                <!-- Exibe a mensagem de erro ou sucesso -->
                <?php if (!empty($message)): ?>
                    <p class="message"><?php echo $message; ?></p>
                <?php endif; ?>

                <p>Já tem uma conta? <a href="login.php">Entre aqui</a></p>
            </div>
        </div>
    <?php endif; ?>
</body>
</html>
