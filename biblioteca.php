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

$user_id = $_SESSION['user_id'];
$sql = "SELECT name FROM Users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$stmt->bind_result($user_name);
$stmt->fetch();

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca - Gamehub</title>
    <link rel="stylesheet" href="biblioteca.css">
    <link rel="icon" type="image/x-icon" href="img/logo.png">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <header>
        <nav id="menu">
            <img src="img/logotipo.png" alt="Logo" id="logo">
            <ul>
                <li><a href="inicio.php">Página Principal</a></li>
                <li><a href="biblioteca.php">Biblioteca</a></li>
                <li><a href="store.php">Store</a></li>
            </ul>
        </nav>
        <div>
            <span id="user-name"><?php echo $user_name; ?></span>
            <a href="logout.php" id="logout-button">Sair</a>
        </div>
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
            <p>Gamehub</p>
            <div id="pop-up">
                <h1>Desenvolvedores</h1>
                <div id="pop-up-conteiner">
                    <img src="img/Luis.jpg" alt="criador" id="criador" class="redondo">
                    <h2>Luis Brandes</h2>
                    <img src="img/Bresolin.png" alt="criador" id="criador">
                    <h2>Gabriel Bresolin</h2>
                    <img src="img/Gabrielle.png" alt="criador" id="criador" class="redondo">
                    <h2>Gabrielle Rocha</h2>
                </div>
            </div>
    </footer>
    <script src="pop-up.js"></script>
</body>

</html>

<?php
$conn->close();
?>
