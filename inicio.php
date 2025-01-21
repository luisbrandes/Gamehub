<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");  // Redireciona para o login se não estiver logado
    exit();
}

echo "<h2>Bem-vindo, " . $_SESSION['user_name'] . "!</h2>";
echo "<p><a href='logout.php'>Sair</a></p>";
?>
