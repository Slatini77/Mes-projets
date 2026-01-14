<?php
$host = 'localhost';
$dbname = 'portfolio';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );

    $pdo->exec("SET NAMES 'utf8mb4'");
    $pdo->exec("SET CHARACTER SET utf8mb4");
    $pdo->exec("SET SESSION collation_connection = 'utf8mb4_general_ci'");

} catch (PDOException $e) {
    http_response_code(500);
    echo "<h3>❌ Erreur de connexion à la base de données :</h3>";
    echo "<pre>" . htmlspecialchars($e->getMessage()) . "</pre>";
    exit;
}
?>
