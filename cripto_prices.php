<?php

$host = 'localhost'; // Seu host do banco de dados
$dbname = 'crypto_prices'; // Nome do banco de dados
$username = 'root'; // Seu usuário MySQL
$password = 'pmjsuser'; // Sua senha MySQL

// Conectar ao banco de dados MySQL usando PDO
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erro de conexão: " . $e->getMessage();
    exit;
}

// Função para obter dados da API da Binance
function getBinancePrice($symbol)
{
    $url = "https://api.binance.com/api/v3/ticker/price?symbol=$symbol";
    $response = file_get_contents($url);

    if ($response === false) {
        echo "Erro ao acessar a API da Binance.";
        exit;
    }

    $data = json_decode($response, true);
    return $data['price']; // Retorna o preço
}

// Lista de criptomoedas que você deseja consultar
$cryptos = ['BTCUSDT', 'ETHUSDT', 'SOLUSDT', 'XRPUSDT'];

// Inserir no banco de dados
foreach ($cryptos as $symbol) {
    // Obter o preço da criptomoeda
    $price = getBinancePrice($symbol);

    // Inserir no banco de dados
    try {
        $stmt = $pdo->prepare("INSERT INTO crypto_prices (symbol, price) VALUES (:symbol, :price)");
        $stmt->execute(['symbol' => $symbol, 'price' => $price]);
        echo "Preço do $symbol inserido com sucesso! Preço: $price\n";
    } catch (PDOException $e) {
        echo "Erro ao salvar no banco de dados para o símbolo $symbol: " . $e->getMessage();
    }
}
