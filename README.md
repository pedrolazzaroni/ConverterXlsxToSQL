# Conversor Excel para MySQL

Aplicação para converter dados de uma tabela Excel para o banco de dados MySQL.

## Instalação

1. Certifique-se de ter o Composer instalado
2. Execute o seguinte comando na pasta do projeto:
   ```
   composer install
   ```
3. Configure o banco de dados no arquivo `config.php`

## Uso

1. Acesse a aplicação pelo navegador: `http://localhost/Laravel/ConverterXlsxToSQL/`
2. Faça upload do arquivo Excel (.xlsx)
3. Clique em "Converter para SQL"
4. Verifique a mensagem de resultado na tela

## Estrutura da Tabela

A tabela será criada com os seguintes campos:
- municipio_instituicao
- uf
- compra
- codigo_br
- descricao_catmat
- unidade_fornecimento
- generico
- cnpj_fornecedor
- fornecedor
- qtd_itens_comprados
- preco_unitario
- preco_total
- data_importacao (automático)
