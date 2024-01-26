<?php

require 'vendor/autoload.php';
use GuzzleHttp\Client;

$client = new Client();

// URL da loja
$url = 'https://www.bulk.com/pt/creatine-monohydrate.html';

// Simular a requisição GET para obter o conteúdo da página
$response = $client->get($url);
$html = (string) $response->getBody();

// Encontrar o conteúdo JSON dentro da tag script
$pattern = '/<script type="application\/ld\+json">(.*?)<\/script>/s';
preg_match($pattern, $html, $matches);

if (isset($matches[1])) {
    // Decodificar o JSON
    $productData = json_decode($matches[1], true);

    // Verificar se a decodificação foi bem-sucedida e se as ofertas estão presentes
    if ($productData && isset($productData['offers'])) {
        // Inicializar a variável do maior preço
        $maiorPreco = null;

        // Iterar sobre as ofertas para encontrar o maior preço
        foreach ($productData['offers'] as $offer) {
            if (isset($offer['price'])) {
                $price = $offer['price'];

                // Atualizar o maior preço se necessário
                if ($maiorPreco === null || $price > $maiorPreco) {
                    $maiorPreco = $price;
                }
            }
        }

        // Imprimir o maior preço se encontrado
        if ($maiorPreco !== null) {
            echo $maiorPreco . '€' . PHP_EOL;
        } else {
            echo 'Não foi possível encontrar preços.';
        }
    } else {
        echo 'Não foi possível encontrar as ofertas.';
    }
} else {
    echo 'Não foi possível encontrar o script JSON.';
}
?>
