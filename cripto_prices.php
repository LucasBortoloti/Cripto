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
function getBinanceData($symbol)
{
    $url = "https://api.binance.com/api/v3/ticker/24hr?symbol=$symbol";
    $response = file_get_contents($url);

    if ($response === false) {
        echo "Erro ao acessar a API da Binance.";
        exit;
    }

    $data = json_decode($response, true);

    return [
        'price' => $data['lastPrice'], // Último preço
        'volume' => $data['quoteVolume'], // Volume em USDT nas últimas 24h
        'price_change' => $data['priceChangePercent'], // Variação percentual nas últimas 24h
    ];
}

// Lista de criptomoedas que você deseja consultar
$cryptos = ['BTCUSDT', 'ETHUSDT', 'SOLUSDT', 'XRPUSDT'];

// Inserir no banco de dados
foreach ($cryptos as $symbol) {
    // Obter os dados da criptomoeda
    $cryptoData = getBinanceData($symbol);

    // Inserir no banco de dados
    try {
        $stmt = $pdo->prepare("INSERT INTO crypto_prices (symbol, price, volume_24h, price_change_24h, timestamp) 
                               VALUES (:symbol, :price, :volume_24h, :price_change_24h, NOW())");
        $stmt->execute([
            'symbol' => $symbol,
            'price' => $cryptoData['price'],
            'volume_24h' => $cryptoData['volume'],
            'price_change_24h' => $cryptoData['price_change']
        ]);
        echo "Preço do $symbol inserido com sucesso!<br>";
    } catch (PDOException $e) {
        echo "Erro ao salvar no banco de dados para o símbolo $symbol: " . $e->getMessage();
    }
}
