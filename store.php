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

if (isset($_POST['adquirir']) || isset($_POST['remover'])) {
    $game_id = $_POST['game_id'];
    $user_id = $_SESSION['user_id'];

    if (isset($_POST['adquirir'])) {
        $sql_check = "SELECT * FROM user_games WHERE user_id = ? AND game_id = ?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param('ii', $user_id, $game_id);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        if ($result_check->num_rows == 0) { 
            $sql_add = "INSERT INTO user_games (user_id, game_id) VALUES (?, ?)";
            $stmt_add = $conn->prepare($sql_add);
            $stmt_add->bind_param('ii', $user_id, $game_id);
            $stmt_add->execute();
        }
    }

    if (isset($_POST['remover'])) {
        $sql_remove = "DELETE FROM user_games WHERE user_id = ? AND game_id = ?";
        $stmt_remove = $conn->prepare($sql_remove);
        $stmt_remove->bind_param('ii', $user_id, $game_id);
        $stmt_remove->execute();
    }

    header("Location: store.php");
    exit();
}

$sql_store = "SELECT id, name, image_url FROM games";
$result_store = $conn->query($sql_store);

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
    <title>Loja - Gamehub</title>
    <link rel="stylesheet" href="store.css">
    <link rel="icon" type="image/x-icon" href="img/logo.png">
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
        <div id="store-container">
            <h1>Loja de Jogos</h1>
            <div id="games-list">
                <?php if ($result_store->num_rows > 0): ?>
                    <?php while ($game = $result_store->fetch_assoc()): ?>
                        <div class="game">
                            <img src="<?php echo $game['image_url']; ?>" alt="<?php echo $game['name']; ?>">
                            <h3><?php echo $game['name']; ?></h3>
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

<?php $conn->close(); ?>
