CREATE TABLE IF NOT EXISTS bps_medicamentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    municipio_instituicao VARCHAR(255),
    uf VARCHAR(2),
    compra VARCHAR(50),
    codigo_br VARCHAR(50),
    descricao_catmat TEXT,
    unidade_fornecimento VARCHAR(100),
    generico VARCHAR(5),
    cnpj_fornecedor VARCHAR(20),
    fornecedor VARCHAR(255),
    qtd_itens_comprados INT,
    preco_unitario DECIMAL(10, 2),
    preco_total DECIMAL(10, 2),
    data_importacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
