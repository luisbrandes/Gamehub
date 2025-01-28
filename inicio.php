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

// Consultar jogos na biblioteca do usuário
$user_id = $_SESSION['user_id'];
$sql_library = "SELECT games.id, games.name, games.image_url FROM games 
                JOIN user_games ON games.id = user_games.game_id
                WHERE user_games.user_id = ?";
$stmt = $conn->prepare($sql_library);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$library_result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gamehub</title>
    <link rel="stylesheet" href="inicio.css">
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
    </header>

    <div id="conteiner">
    <div id="banner-conteiner">
            <h1>Gamehub</h1>
            <div id="banner">
                <img src="img/BlackMith-banner.png" alt="Banner 1">
            </div>
            <!-- Botões de seleção de banner -->
            <div id="banner-buttons">
                <div class="banner-button active" data-index="0">
                    <img src="img/BlackMith-banner.png" alt="BlackMith">
                    <span>BlackMith</span>
                </div>
                <div class="banner-button" data-index="1">
                    <img src="img/fortinite-banner.png" alt="Fortnite">
                    <span>Fortnite</span>
                </div>
                <div class="banner-button" data-index="2">
                    <img src="img/Its-take-two-banner.png" alt="Its Take Two">
                    <span>Its Take Two</span>
                </div>
                <div class="banner-button" data-index="3">
                    <img src="img/fall-guys-banner.png" alt="Fall Guys">
                    <span>Fall Guys</span>
                </div>
            </div>
        </div>   

        <div id="biblioteca-conteiner">
            <h1>Biblioteca</h1>
            <div id="biblioteca">
                <?php if ($library_result->num_rows > 0): ?>
                    <!-- Exibe os jogos da biblioteca -->
                    <?php while ($game = $library_result->fetch_assoc()): ?>
                        <div class="game">
                            <img src="<?php echo $game['image_url']; ?>" alt="<?php echo $game['name']; ?>">
                            <h3><?php echo $game['name']; ?></h3>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>Você ainda não comprou nenhum jogo.</p>
                <?php endif; ?>
                <div id="abrir-biblioteca">
                    <a href="biblioteca_completa.php">Abrir Biblioteca Completa</a>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <div id="footer">
            <p>Gamehub - 2025</p>
        </div>
    </footer>
    <script src="banner.js"></script>
</body>

</html>

<?php
$conn->close();
?>
