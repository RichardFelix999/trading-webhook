<?php
require __DIR__ . '/vendor/autoload.php';
use Dotenv\Dotenv;

// Load .env file
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Database credentials from .env
$db_host = $_ENV['DB_HOST'];
$db_port = $_ENV['DB_PORT'];
$db_name = $_ENV['DB_NAME'];
$db_user = $_ENV['DB_USER'];
$db_pass = $_ENV['DB_PASS'];

try {
    // Connect to PostgreSQL
    $dsn = "pgsql:host=$db_host;port=$db_port;dbname=$db_name;";
    $pdo = new PDO($dsn, $db_user, $db_pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    die(json_encode(["error" => "Database connection failed: " . $e->getMessage()]));
}

// Read webhook payload
$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data['symbol'], $data['price'], $data['alert_time'])) {
    http_response_code(400);
    die(json_encode(["error" => "Invalid payload"]));
}

// Extract data
$symbol = $data['symbol'];
$price = $data['price'];
$alert_time = $data['alert_time'];

try {
    // Insert data into PostgreSQL
    $stmt = $pdo->prepare("INSERT INTO tradingview_alerts (symbol, price, alert_time) VALUES (:symbol, :price, :alert_time)");
    $stmt->execute([
        ':symbol' => $symbol,
        ':price' => $price,
        ':alert_time' => $alert_time
    ]);

    http_response_code(200);
    echo json_encode(["success" => "Alert stored successfully"]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Failed to insert data: " . $e->getMessage()]);
}
?>
