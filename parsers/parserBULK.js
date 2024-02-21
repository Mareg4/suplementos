const axios = require('axios');
const cheerio = require('cheerio');
const best_calculator = require('./best_calculator_JS'); // calcula o melhor racio de €/kg e o melhor peso e preco

// TORUN: node --max-http-header-size 8500 parserBULK.js
async function fetchAndPrintProductInfo(url) {
  try {
    const response = await axios.get(url);
    const $ = cheerio.load(response.data);
    
    // Encontrar o script com type="application/ld+json"
    const ldJsonScript = $('script[type="application/ld+json"]').html();
    
    // Parse do JSON
    const productData = JSON.parse(ldJsonScript);

    // Verificar se há ofertas e se estão em estoque
    if (productData.offers && Array.isArray(productData.offers)) {
      const inStockOffers = productData.offers.filter(offer => offer.availability === 'InStock');
      const produto = productData.name;
      
      // Imprimir listas de peso e preço
      const weights = [];
      const prices = [];

      inStockOffers.forEach(offer => {
        const weightMatch = offer.sku.split('-'); // Extrair números do SKU

        const weight = weightMatch ? parseInt(weightMatch[3]) : null;
        
        if (weight !== null) {
          weights.push(weight/1000);
          prices.push(offer.price);
        }
      });

      ret = best_calculator(prices, weights);

      best_weight = ret._best_weight;
      best_price = ret._best_price;
      min = ret._min;

      /*console.log(weights);
      console.log(prices);
      console.log(final);*/

      console.log("Produto:", produto);
      console.log("Preço:",best_price, "\nPeso:",best_weight, "\nRácio €/kg:",min.toFixed(2)); //
    } else {
      console.log('Não foram encontradas ofertas ou nenhum produto em estoque.');
    }
  } catch (error) {
    console.error('Erro ao fazer a solicitação:', error.message);
  }
}

const siteURL = 'https://www.bulk.com/pt/proteina-whey.html';
fetchAndPrintProductInfo(siteURL);
