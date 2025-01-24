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

// Obter os últimos registros de cada criptomoeda e ordená-los
$query = "
    SELECT symbol, price, timestamp
    FROM crypto_prices
    WHERE (symbol, timestamp) IN (
        SELECT symbol, MAX(timestamp)
        FROM crypto_prices
        GROUP BY symbol
    )
    ORDER BY
        FIELD(SUBSTRING(symbol, 1, 3), 'BTC', 'ETH', 'SOL', 'XRP'),
        timestamp DESC
";

$stmt = $pdo->query($query);
$cryptos = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mercado de Criptomoedas</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        /* Global Styles */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #0e1117;
            color: #fff;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 30px auto;
        }

        .titulo {
            text-align: center;
            margin-bottom: 30px;
            color: #00bcd4;
            /* Cinza elegante */
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 2rem;
            color: #ffffff;
        }

        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #161b22;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);
        }

        th,
        td {
            padding: 15px;
            text-align: left;
        }

        th {
            background-color: #1a1f26;
            font-weight: 600;
            color: #8899a6;
            text-transform: uppercase;
            font-size: 0.9rem;
        }

        td {
            font-size: 0.95rem;
            border-bottom: 1px solid #242931;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover {
            background-color: #242931;
        }

        /* Styles for Crypto Icons */
        .crypto {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .crypto img {
            width: 30px;
            height: 30px;
            border-radius: 50%;
        }

        .crypto-name {
            font-weight: bold;
            font-size: 1.1em;
            color: #d3d3d3;
        }


        .price {
            color: #66bb6a;
        }


        .updated {
            font-size: 0.85rem;
            color: #8899a6;
        }

        /* Responsive Design */
        @media (max-width: 768px) {

            th,
            td {
                font-size: 0.8rem;
                padding: 10px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <h1 class="titulo">Mercado de Criptomoedas</h1>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Criptomoeda</th>
                    <th>Preço</th>
                    <th>Última Atualização</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cryptos as $index => $crypto): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td class="crypto-name"><?= htmlspecialchars(substr($crypto['symbol'], 0, 3)) ?></td>
                        <td class="price"><?= number_format($crypto['price'], 2) ?> USDT</td>
                        <td class="updated"><?= date('d/m/Y H:i:s', strtotime($crypto['timestamp'])) ?></td>
                    </tr>

                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>