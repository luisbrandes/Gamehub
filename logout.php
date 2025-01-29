<?php
session_start();
session_destroy();  // Destruir a sessão
header("Location: login.php");  // Redirecionar para a página de login
exit();
?>