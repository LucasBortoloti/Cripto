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

$sql = "SELECT cp1.*
        FROM crypto_prices cp1
        INNER JOIN (
            SELECT symbol, MAX(timestamp) AS latest_timestamp
            FROM crypto_prices
            GROUP BY symbol
        ) cp2 ON cp1.symbol = cp2.symbol AND cp1.timestamp = cp2.latest_timestamp
        ORDER BY cp1.symbol ASC";


$stmt = $pdo->query($sql);
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

        /* Positive and Negative Colors */
        .positive {
            color: #66bb6a;
        }

        .negative {
            color: #e57373;
        }

        .updated {
            font-size: 0.85rem;
            color: #8899a6;
        }

        .price {
            color: #66bb6a;

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
                    <th>Logo</th>
                    <th>Criptomoeda</th>
                    <th>Preço</th>
                    <th>Volume</th>
                    <th>Variação (24h)</th>
                    <th>Última Atualização</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cryptos as $index => $crypto): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td>
                            <img src="img/<?= htmlspecialchars($crypto['symbol']) ?>.png"
                                alt="<?= htmlspecialchars($crypto['symbol']) ?>"
                                width="40">
                        </td>
                        <td><?= htmlspecialchars(substr($crypto['symbol'], 0, 3)) ?></td>
                        <td class="price"><?= number_format($crypto['price'], 2) ?> USDT</td>
                        <td><?= number_format($crypto['volume_24h'], 2) ?> USDT</td>
                        <td class="<?= ($crypto['price_change_24h'] >= 0) ? 'positive' : 'negative' ?>">
                            <?= number_format($crypto['price_change_24h'], 2) ?>%
                        </td>
                        <td class="updated">
                            <?= isset($crypto['timestamp']) ? date('d/m/Y H:i:s', strtotime($crypto['timestamp'])) : 'N/A' ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    </div>
</body>

</html>