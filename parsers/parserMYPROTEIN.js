const fs = require('fs');
const axios = require('axios');
const cheerio = require('cheerio');
const best_calculator = require('./best_calculator_JS');

async function requests(url_price, url_stock, peso, prices, weights) {
    try {
        const responseStock = await axios.get(url_stock);
        const $stock = cheerio.load(responseStock.data);

        const stockText = $stock('.productStockInformation_suffix.productStockInformation_text').text().trim();

        if (stockText === 'Em stock') {
            const responsePrice = await axios.get(url_price);
            const $price = cheerio.load(responsePrice.data);

            var priceText = $price('.productPrice_price').text().trim();
            priceText = priceText.replace('€', '').trim();

            const priceFloat = parseFloat(priceText, 10);
            const pesoFloat = parseFloat(peso, 10);
            //const racioPrecoPeso = (priceFloat / pesoFloat);

            prices.push(priceFloat);
            weights.push(pesoFloat);
            //ratios.push(racioPrecoPeso);

        } 

    } catch (error) {
        console.error('Erro ao fazer a solicitação:', error.message);
        return null; // Retorna null em caso de erro
    }
}

async function main(){
    const filePath = './myProteinData.json';

    try {
        const jsonData = require(filePath);
    
        // Exemplo: Acessando dados específicos
        
        
        for (const produto in jsonData) {
            //const ratios = [];
            const prices = [];
            const weights = [];
            
            const promises = [];
    
            for (const key in jsonData[produto]) {
                const url_price = "https://www.myprotein.pt/" + key + ".price";
                const url_stock = "https://www.myprotein.pt/" + key + ".stock";
                
                promises.push(requests(url_price, url_stock, jsonData[produto][key], prices, weights));
            }
            await Promise.all(promises);
            
            /*console.log(weights);
            console.log(prices);
            console.log(ratios);*/

            ret = best_calculator(prices, weights);

            best_weight = ret._best_weight;
            best_price = ret._best_price;
            min = ret._min;

            console.log("Produto: " + produto + "\nPreço: " + best_price + "\nPeso: " + best_weight + "\nRácio €/kg: " + min.toFixed(2));
        }
    } catch (error) {
        console.error('Erro ao ler o arquivo JSON:', error.message);
    }
}

main();