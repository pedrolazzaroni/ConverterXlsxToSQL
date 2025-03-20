<?php
// Verifica se é uma requisição de upload
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

// Verifica se o arquivo foi enviado
if (!isset($_FILES['arquivo']) || $_FILES['arquivo']['error'] !== UPLOAD_ERR_OK) {
    header('Location: index.php?error=Erro no upload do arquivo.');
    exit;
}

// Verifica a extensão do arquivo
$extensao = pathinfo($_FILES['arquivo']['name'], PATHINFO_EXTENSION);
if ($extensao !== 'xlsx') {
    header('Location: index.php?error=Por favor, envie um arquivo Excel (.xlsx)');
    exit;
}

// Carrega o autoloader do Composer
require 'vendor/autoload.php';
require 'config.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

try {
    // Conecta ao banco de dados
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS);
    
    // Verifica a conexão
    if ($conn->connect_error) {
        throw new Exception("Falha na conexão: " . $conn->connect_error);
    }
    
    // Cria o banco de dados se não existir
    $conn->query("CREATE DATABASE IF NOT EXISTS " . DB_NAME);
    $conn->select_db(DB_NAME);
    
    // Carrega o script SQL para criar a tabela
    $sqlScript = file_get_contents('create_table.sql');
    if (!$conn->multi_query($sqlScript)) {
        throw new Exception("Erro ao criar tabela: " . $conn->error);
    }
    
    // Espera até que todas as queries sejam executadas
    while ($conn->more_results() && $conn->next_result());
    
    // Carrega o arquivo Excel
    $spreadsheet = IOFactory::load($_FILES['arquivo']['tmp_name']);
    $worksheet = $spreadsheet->getActiveSheet();
    $rows = $worksheet->toArray();
    
    // Remove o cabeçalho (primeira linha)
    $headers = array_shift($rows);
    
    // Prepara a query de inserção
    $stmt = $conn->prepare("INSERT INTO bps_medicamentos 
        (municipio_instituicao, uf, compra, codigo_br, descricao_catmat, 
        unidade_fornecimento, generico, cnpj_fornecedor, fornecedor, 
        qtd_itens_comprados, preco_unitario, preco_total) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    if (!$stmt) {
        throw new Exception("Erro na preparação da query: " . $conn->error);
    }
    
    $stmt->bind_param(
        "sssssssssddd", 
        $municipio_instituicao, $uf, $compra, $codigo_br, $descricao_catmat,
        $unidade_fornecimento, $generico, $cnpj_fornecedor, $fornecedor,
        $qtd_itens_comprados, $preco_unitario, $preco_total
    );
    
    // Contador de registros inseridos
    $contador = 0;
    
    // Insere os dados
    foreach ($rows as $row) {
        if (count($row) >= 12) { // Verifica se a linha tem todos os campos necessários
            $municipio_instituicao = $row[0];
            $uf = $row[1];
            $compra = $row[2];
            $codigo_br = $row[3];
            $descricao_catmat = $row[4];
            $unidade_fornecimento = $row[5];
            $generico = $row[6];
            $cnpj_fornecedor = $row[7];
            $fornecedor = $row[8];
            $qtd_itens_comprados = (int)$row[9];
            $preco_unitario = (float)str_replace(',', '.', $row[10]);
            $preco_total = (float)str_replace(',', '.', $row[11]);
            
            if ($stmt->execute()) {
                $contador++;
            }
        }
    }
    
    $stmt->close();
    $conn->close();
    
    // Redireciona com mensagem de sucesso
    header("Location: index.php?success=Conversão concluída! $contador registros inseridos com sucesso.");
    
} catch (Exception $e) {
    // Redireciona com mensagem de erro
    header("Location: index.php?error=" . urlencode($e->getMessage()));
}
?>
