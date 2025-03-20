<?php
// Verifica se é uma requisição de upload
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

// Verifica se o arquivo foi enviado
if (!isset($_FILES['arquivo']) || $_FILES['arquivo']['error'] !== UPLOAD_ERR_OK) {
    http_response_code(400);
    echo "Erro no upload do arquivo.";
    exit;
}

// Verifica a extensão do arquivo
$extensao = pathinfo($_FILES['arquivo']['name'], PATHINFO_EXTENSION);
if ($extensao !== 'xlsx') {
    http_response_code(400);
    echo "Por favor, envie um arquivo Excel (.xlsx)";
    exit;
}

// Carrega o autoloader do Composer
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

try {
    // Obtém o nome do arquivo sem extensão para usar como nome da tabela
    $nomeArquivo = pathinfo($_FILES['arquivo']['name'], PATHINFO_FILENAME);
    // Limpa o nome da tabela preservando caracteres UTF-8, removendo apenas caracteres problemáticos para SQL
    $nomeTabela = preg_replace('/[^\p{L}\p{N}_]/u', '_', $nomeArquivo);
    $nomeTabela = trim($nomeTabela, '_');
    $nomeTabela = strtolower($nomeTabela);
    
    // Se o nome da tabela ficar vazio ou começar com número, adiciona prefixo
    if (empty($nomeTabela) || is_numeric(substr($nomeTabela, 0, 1))) {
        $nomeTabela = 'tabela_' . $nomeTabela;
    }
    
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
    
    // Limpa os cabeçalhos preservando acentos e caracteres UTF-8
    $cleanHeaders = [];
    foreach ($headers as $index => $header) {
        if (empty(trim($header))) {
            $cleanHeaders[] = 'coluna_' . ($index + 1);
        } else {
            // Preserva acentos e caracteres UTF-8, remove apenas caracteres problemáticos para SQL
            $cleanHeader = preg_replace('/[^\p{L}\p{N}_\s]/u', '', trim($header));
            $cleanHeader = preg_replace('/\s+/', '_', $cleanHeader);
            
            // Garante que o nome da coluna seja válido para SQL
            if (empty($cleanHeader) || is_numeric(substr($cleanHeader, 0, 1))) {
                $cleanHeader = 'coluna_' . ($index + 1) . '_' . $cleanHeader;
            }
            
            $cleanHeaders[] = $cleanHeader;
        }
    }
    
    // Verifica se há cabeçalhos válidos
    if (count($cleanHeaders) < 1) {
        throw new Exception("Não foram encontrados cabeçalhos válidos na primeira linha do Excel.");
    }
    
    // Cria o arquivo SQL
    $timestamp = time();
    $sqlFile = $nomeTabela . '_' . $timestamp . '.sql';
    $sqlFilePath = __DIR__ . '/' . $sqlFile;
    $sqlContent = '';
    
    // Adiciona comentário inicial
    $sqlContent .= "-- SQL gerado a partir do arquivo: " . $_FILES['arquivo']['name'] . "\n";
    $sqlContent .= "-- Data de geração: " . date('Y-m-d H:i:s') . "\n";
    $sqlContent .= "-- Codificação: UTF-8\n\n";
    
    // Adiciona configuração de charset
    $sqlContent .= "SET NAMES utf8mb4;\n";
    $sqlContent .= "SET FOREIGN_KEY_CHECKS = 0;\n\n";
    
    // Cria o script para criar a tabela
    $sqlContent .= "CREATE TABLE IF NOT EXISTS `$nomeTabela` (\n";
    $sqlContent .= "    `id` INT AUTO_INCREMENT PRIMARY KEY,\n";
    
    foreach ($cleanHeaders as $header) {
        $sqlContent .= "    `$header` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,\n";
    }
    
    $sqlContent .= "    `data_importacao` TIMESTAMP DEFAULT CURRENT_TIMESTAMP\n";
    $sqlContent .= ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;\n\n";
    
    // Cria os INSERTs
    $totalLinhas = 0;
    foreach ($rows as $row) {
        // Ajusta o tamanho da linha para corresponder ao número de cabeçalhos
        $rowData = array_slice($row, 0, count($cleanHeaders));
        // Preenche com NULL se a linha tiver menos valores que cabeçalhos
        while (count($rowData) < count($cleanHeaders)) {
            $rowData[] = null;
        }
        
        // Verifica se a linha tem pelo menos um valor não vazio
        if (array_filter($rowData, function($cell) { return $cell !== null && $cell !== ''; })) {
            $sqlContent .= "INSERT INTO `$nomeTabela` (";
            
            // Adiciona os nomes das colunas
            $sqlContent .= "`" . implode("`, `", $cleanHeaders) . "`";
            
            $sqlContent .= ") VALUES (";
            
            // Adiciona os valores
            $values = [];
            foreach ($rowData as $cell) {
                if ($cell === null || $cell === '') {
                    $values[] = "NULL";
                } else {
                    // Escapa aspas simples e trata caracteres especiais
                    $cell = str_replace("'", "''", $cell);
                    $values[] = "'$cell'";
                }
            }
            
            $sqlContent .= implode(", ", $values);
            $sqlContent .= ");\n";
            $totalLinhas++;
        }
    }
    
    $sqlContent .= "\nSET FOREIGN_KEY_CHECKS = 1;\n";
    
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
    // Retorna erro para a requisição AJAX
    http_response_code(500);
    echo $e->getMessage();
    exit;
}
?>
