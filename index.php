<?php
include('simple_html_dom.php');

// Conectar-se ao banco de dados
$conn = new mysqli("localhost", "usuario", "senha", "banco_de_dados");
if ($conn->connect_error) {
    die("Erro na conexÃ£o com o banco de dados: " . $conn->connect_error);
}

// Fazer o web scraping
$url = "https://www.prozis.com/pt/pt/prozis/creatina-mono-hidratada-700-g";
$html = file_get_html($url);

// Extrair dados
$precoElement = $html->find('p.final-price', 0);

// Apresentar os valores no HTML
?>
<!DOCTYPE html>
<html>
<head>
    <title>Web Scraping Exemplo</title>
</head>
<body>
    <p itemprop="price" class="final-price"> <?php echo $preco; ?> </p>
</body>
</html>