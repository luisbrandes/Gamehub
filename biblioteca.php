<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Conexão com o banco de dados
$servername = "sql204.infinityfree.com";
$username = "if0_38145611";
$password = "l906DqNGC2Csj4C";
$database = "if0_38145611_userscredentials";
$conn = new mysqli($servername, $username, $password, $database);

// Verifica a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Ação de remover jogo
if (isset($_POST['remover'])) {
    $game_id = $_POST['game_id'];
    $user_id = $_SESSION['user_id'];

    // Remover jogo da biblioteca do usuário
    $sql_remove = "DELETE FROM user_games WHERE user_id = ? AND game_id = ?";
    $stmt_remove = $conn->prepare($sql_remove);
    $stmt_remove->bind_param('ii', $user_id, $game_id);
    $stmt_remove->execute();

    // Redireciona para a biblioteca após a ação
    header("Location: biblioteca.php");
    exit();
}

// Consultar os jogos que o usuário adquiriu
$sql_library = "
    SELECT g.id, g.name, g.image_url 
    FROM games g
    INNER JOIN user_games ug ON g.id = ug.game_id
    WHERE ug.user_id = ?";
$stmt_library = $conn->prepare($sql_library);
$stmt_library->bind_param('i', $_SESSION['user_id']);
$stmt_library->execute();
$result_library = $stmt_library->get_result();

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca - Gamehub</title>
    <link rel="stylesheet" href="store.css">
    <link rel="icon" type="image/x-icon" href="img/logo.png">
</head>

<body>
    <header>
        <nav id="menu">
            <img src="img/logotipo.png" alt="Logo" id="logo">
            <ul>
                <li><a href="pagina-principal.html">Página Principal</a></li>
                <li><a href="biblioteca.php">Biblioteca</a></li>
                <li><a href="store.php">Store</a></li>
            </ul>
        </nav>
    </header>

    <div id="conteiner">
        <div id="library-container">
            <h1>Minha Biblioteca</h1>
            <div id="games-list">
                <?php if ($result_library->num_rows > 0): ?>
                    <!-- Exibe todos os jogos da biblioteca -->
                    <?php while ($game = $result_library->fetch_assoc()): ?>
                        <div class="game">
                            <img src="<?php echo $game['image_url']; ?>" alt="<?php echo $game['name']; ?>">
                            <h3><?php echo $game['name']; ?></h3>
                            <!-- Formulário para remover jogos -->
                            <form action="biblioteca.php" method="POST">
                                <input type="hidden" name="game_id" value="<?php echo $game['id']; ?>">
                                <button type="submit" name="remover">Remover</button>
                            </form>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>Não há jogos na sua biblioteca no momento.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <footer>
        <div id="footer">
            <p>Gamehub - 2025</p>
        </div>
    </footer>
</body>

</html>

<?php
$conn->close();
?>
