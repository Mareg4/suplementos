import requests
from bs4 import BeautifulSoup

# TODO VERIFICAR SE VERIFICA O STOCK

def padronizar_peso_peso_ou_preco(valor_str):
    # Remove espaços em branco extras e converte para minúsculas para facilitar a comparação
    valor_str = valor_str.strip().lower()

    # Verifica se a string contém o símbolo '€'
    if '€' in valor_str:
        valor = float(valor_str.replace('€', '').strip())
    elif 'kg' in valor_str:
        valor = float(valor_str.replace('kg', '').strip())
    elif 'gramas' or 'g' in valor_str:
        valor = float(valor_str.replace('gramas', '').replace('g', '').strip())
        valor /= 1000
    else:
        # Se não contém '€', 'gramas', 'g' ou 'kg', assume que já está no formato desejado
        valor = float(valor_str)

    return valor


def calcular_divisao(preco, peso): #confirmar se funciona
    # Calcula a divisão preço/peso e retorna o resultado com no máximo duas casas decimais
    resultado = preco / peso
    return round(resultado, 2)


def parserZUMUB():
    # URL do site
    url = "https://www.zumub.com/PT/proteinas/whey/100-whey-concentrate-1kg-p-10326#10328"

    # Fazendo a solicitação HTTP
    response = requests.get(url)

    # Verifica se a solicitação foi bem-sucedida (código 200)
    if response.status_code == 200:
        # Obtendo o conteúdo HTML da página
        html_content = response.text

        # Analisando o HTML com BeautifulSoup
        soup = BeautifulSoup(html_content, 'html.parser')

        # Encontrando o elemento <select> pelo ID
        select_element = soup.find('select', {'id': 'master_child'})

        # Verificando se o elemento <select> foi encontrado
        if select_element:
            
            title_text = soup.title.string.strip()
            index_zumub = title_text.find("Zumub")
            
            if index_zumub != -1:
               produto = title_text[:index_zumub].strip()
            
            # Encontrando todas as tags <option> dentro do elemento <select>
            options = select_element.find_all('option')
            
            
            # Inicializando listas para armazenar preços e pesos
            precos = []
            pesos = []

            # Extraindo e armazenando o preço e peso de cada tag <option>
            for option in options:
                text = option.get_text(strip=True)
                peso, preco = map(str.strip, text.split('-'))
                if ("Esgotado" or "esgotado") not in preco:
                    peso_padronizado = padronizar_peso_peso_ou_preco(peso)
                    preco_padronizado = padronizar_peso_peso_ou_preco(preco)
                    pesos.append(peso_padronizado)
                    precos.append(preco_padronizado)

            # Calculando a divisão preço/peso para cada par
            divisoes = [calcular_divisao(preco, peso) for preco, peso in zip(precos, pesos)]

            # Encontrando o índice do menor resultado
            indice_menor_divisao = divisoes.index(min(divisoes))

            # Imprimindo as informações do menor resultado
            print("Produto:", produto)
            print(f"Preço: {precos[indice_menor_divisao]}")
            print(f"Peso: {int(pesos[indice_menor_divisao])}")
            print(f"Rácio €/kg: {divisoes[indice_menor_divisao]}")

        else:
            print("Elemento <select> não encontrado.")

    else:
        print(f"Erro na solicitação. Código de status: {response.status_code}")

if __name__ == "__main__":
    parserZUMUB()
