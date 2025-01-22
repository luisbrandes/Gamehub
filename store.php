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

// Ação de Adquirir ou Remover jogo
if (isset($_POST['adquirir']) || isset($_POST['remover'])) {
    $game_id = $_POST['game_id'];
    $user_id = $_SESSION['user_id'];

    if (isset($_POST['adquirir'])) {
        // Adicionar jogo à biblioteca do usuário
        $sql_add = "INSERT INTO user_games (user_id, game_id) VALUES (?, ?)";
        $stmt_add = $conn->prepare($sql_add);
        $stmt_add->bind_param('ii', $user_id, $game_id);
        $stmt_add->execute();
    }

    if (isset($_POST['remover'])) {
        // Remover jogo da biblioteca do usuário
        $sql_remove = "DELETE FROM user_games WHERE user_id = ? AND game_id = ?";
        $stmt_remove = $conn->prepare($sql_remove);
        $stmt_remove->bind_param('ii', $user_id, $game_id);
        $stmt_remove->execute();
    }

    // Redireciona para a loja após a ação
    header("Location: store.php");
    exit();
}

// Consultar todos os jogos disponíveis na loja
$sql_store = "SELECT id, name, image_url FROM games";
$result_store = $conn->query($sql_store);

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loja - Gamehub</title>
    <link rel="stylesheet" href="hub.css">
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
        <div id="store-container">
            <h1>Loja de Jogos</h1>
            <div id="games-list">
                <?php if ($result_store->num_rows > 0): ?>
                    <!-- Exibe todos os jogos da loja -->
                    <?php while ($game = $result_store->fetch_assoc()): ?>
                        <div class="game">
                            <img src="<?php echo $game['image_url']; ?>" alt="<?php echo $game['name']; ?>">
                            <h3><?php echo $game['name']; ?></h3>
                            <!-- Formulários para adquirir e remover jogos -->
                            <form action="store.php" method="POST">
                                <input type="hidden" name="game_id" value="<?php echo $game['id']; ?>">
                                <button type="submit" name="adquirir">Adquirir</button>
                                <button type="submit" name="remover">Remover</button>
                            </form>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>Não há jogos disponíveis na loja no momento.</p>
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
