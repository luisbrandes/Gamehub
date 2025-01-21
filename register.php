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
        echo "Este email já está registrado!";
    } else {
        // Criptografar a senha
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);

        // Inserir o novo usuário no banco de dados
        $sql = "INSERT INTO Users (name, email, passwordHash) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sss', $name, $email, $passwordHash);

        if ($stmt->execute()) {
            echo "Conta criada com sucesso! Agora você pode <a href='login.html'>entrar</a>.";
        } else {
            echo "Erro ao criar a conta: " . $stmt->error;
        }
    }
}
?>
