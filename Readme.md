Para fazer com que os dados sejam registrados no banco de dados sem precisar ficar atualizando a página, é possível utilizar o:

Cron Job

Utilizando os comandos:

crontab -e

E dentro do arquivo colocar esse comando:

*/2 * * * * /usr/bin/php /var/www/html/cripto/cripto_prices.php

Nesse exemplo eu coloquei para ele repetir a cada 2 min, e na segunda parte está o diretório do arquivo PHP

A Api utilizada para essa aplicação foi a da Binance

![print tela](img/cripto.jpg)

:)