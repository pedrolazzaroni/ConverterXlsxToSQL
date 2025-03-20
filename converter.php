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

use PhpOffice\PhpSpreadsheet\IOFactory;

try {
    // Obtém o nome do arquivo sem extensão para usar como nome da tabela
    $nomeArquivo = pathinfo($_FILES['arquivo']['name'], PATHINFO_FILENAME);
    // Limpa o nome da tabela (remove espaços e caracteres especiais)
    $nomeTabela = preg_replace('/[^a-zA-Z0-9_]/', '_', $nomeArquivo);
    $nomeTabela = strtolower($nomeTabela);
    
    // Carrega o arquivo Excel
    $spreadsheet = IOFactory::load($_FILES['arquivo']['tmp_name']);
    $worksheet = $spreadsheet->getActiveSheet();
    $rows = $worksheet->toArray();
    
    // Verifica se há pelo menos uma linha (cabeçalho)
    if (count($rows) < 1) {
        throw new Exception("O arquivo Excel está vazio.");
    }
    
    // Pega a primeira linha como cabeçalho
    $headers = array_shift($rows);
    
    // Limpa os cabeçalhos (remove espaços e caracteres especiais)
    $headers = array_map(function($header) {
        return preg_replace('/[^a-zA-Z0-9_]/', '_', trim($header));
    }, $headers);
    
    // Verifica se há cabeçalhos válidos
    $headers = array_filter($headers, function($header) {
        return !empty($header);
    });
    
    if (count($headers) < 1) {
        throw new Exception("Não foram encontrados cabeçalhos válidos na primeira linha do Excel.");
    }
    
    // Cria o arquivo SQL
    $sqlFile = 'sql_' . time() . '.sql';
    $sqlFilePath = __DIR__ . '/' . $sqlFile;
    $sqlContent = '';
    
    // Adiciona comentário inicial
    $sqlContent .= "-- SQL gerado a partir do arquivo: " . $_FILES['arquivo']['name'] . "\n";
    $sqlContent .= "-- Data de geração: " . date('Y-m-d H:i:s') . "\n\n";
    
    // Cria o script para criar a tabela
    $sqlContent .= "CREATE TABLE IF NOT EXISTS `$nomeTabela` (\n";
    $sqlContent .= "    `id` INT AUTO_INCREMENT PRIMARY KEY,\n";
    
    foreach ($headers as $header) {
        if (!empty($header)) {
            $sqlContent .= "    `$header` TEXT,\n";
        }
    }
    
    $sqlContent .= "    `data_importacao` TIMESTAMP DEFAULT CURRENT_TIMESTAMP\n";
    $sqlContent .= ");\n\n";
    
    // Cria os INSERTs
    foreach ($rows as $row) {
        // Verifica se a linha tem dados
        $rowData = array_slice($row, 0, count($headers));
        if (array_filter($rowData, function($cell) { return !empty($cell); })) {
            $sqlContent .= "INSERT INTO `$nomeTabela` (";
            
            // Adiciona os nomes das colunas
            $sqlContent .= "`" . implode("`, `", $headers) . "`";
            
            $sqlContent .= ") VALUES (";
            
            // Adiciona os valores
            $values = [];
            foreach ($rowData as $cell) {
                if ($cell === null || $cell === '') {
                    $values[] = "NULL";
                } else {
                    // Escapa aspas simples
                    $cell = str_replace("'", "''", $cell);
                    $values[] = "'$cell'";
                }
            }
            
            $sqlContent .= implode(", ", $values);
            $sqlContent .= ");\n";
        }
    }
    
    // Salva o arquivo SQL
    if (file_put_contents($sqlFilePath, $sqlContent) === false) {
        throw new Exception("Não foi possível salvar o arquivo SQL.");
    }
    
    // Define headers para download do arquivo
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . $sqlFile . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($sqlFilePath));
    readfile($sqlFilePath);
    
    // Remove o arquivo após o download
    unlink($sqlFilePath);
    exit;
    
} catch (Exception $e) {
    // Redireciona com mensagem de erro
    header("Location: index.php?error=" . urlencode($e->getMessage()));
    exit;
}
?>
