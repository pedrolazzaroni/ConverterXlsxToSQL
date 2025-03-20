<?php
// Verificar se o processamento foi concluído
$success = isset($_GET['success']) ? $_GET['success'] : null;
$error = isset($_GET['error']) ? $_GET['error'] : null;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conversor Xlsx para SQL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="shortcut icon" href="./assets/favicon/favicon.png" type="image/x-icon">
    <style>
        :root {
            --primary-color: #ff7700;
            --secondary-color: #212121;
            --accent-color: #ff9d40;
            --light-color: #f8f9fa;
            --dark-color: #111111;
        }
        
        body {
            background-color: #f5f7fa;
            min-height: 100vh;
            font-family: 'Poppins', sans-serif;
        }
        
        .navbar {
            background-color: var(--secondary-color);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }
        
        .main-container {
            margin-top: 2rem;
            margin-bottom: 2rem;
        }
        
        .card {
            border-radius: 15px;
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }
        
        .card-header {
            background-color: var(--primary-color);
            padding: 1.5rem;
            border-bottom: none;
        }
        
        .card-body {
            padding: 2rem;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border: none;
            padding: 0.8rem 2rem;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(255, 119, 0, 0.3);
        }
        
        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(255, 119, 0, 0.5);
            background-color: #ff8800;
        }
        
        .form-control {
            border-radius: 10px;
            padding: 0.8rem 1.2rem;
            border: 1px solid #e0e0e0;
        }
        
        .file-upload-wrapper {
            position: relative;
            margin-bottom: 15px;
            border: 2px dashed #ff9d40;
            border-radius: 15px;
            padding: 2rem;
            text-align: center;
            transition: all 0.3s ease;
            background-color: rgba(255, 255, 255, 0.6);
        }
        
        .file-upload-wrapper:hover {
            background-color: rgba(255, 255, 255, 0.9);
            border-color: var(--primary-color);
        }
        
        .file-upload-input {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }
        
        .file-upload-text {
            margin-top: 1rem;
        }
        
        .step-card {
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            background-color: white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            border-left: 4px solid var(--primary-color);
        }
        
        .step-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }
        
        .step-number {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background-color: var(--primary-color);
            color: white;
            border-radius: 50%;
            font-weight: bold;
            margin-right: 15px;
        }
        
        .features-list li {
            margin-bottom: 0.8rem;
        }
        
        .alert {
            border-radius: 10px;
            border: none;
            padding: 1rem 1.5rem;
        }
        
        .selected-file {
            display: none;
            margin-top: 15px;
            padding: 10px;
            background-color: rgba(255, 119, 0, 0.1);
            border-radius: 10px;
        }
        
        .text-primary {
            color: var(--primary-color) !important;
        }
        
        footer {
            background: var(--secondary-color);
        }
        
        .features-list i.text-success {
            color: var(--primary-color) !important;
        }
        
        .fas.fa-file-excel,
        .fas.fa-lightbulb {
            color: var(--primary-color) !important;
        }
        
        /* Estilos da tela de carregamento */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            display: none;
        }
        
        .loading-spinner {
            width: 80px;
            height: 80px;
            border: 8px solid #f3f3f3;
            border-top: 8px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-bottom: 20px;
        }
        
        .loading-text {
            color: white;
            font-size: 1.5rem;
            font-weight: 600;
            text-align: center;
        }
        
        .loading-progress {
            width: 300px;
            height: 8px;
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 4px;
            margin-top: 15px;
            overflow: hidden;
        }
        
        .loading-progress-bar {
            height: 100%;
            background-color: var(--primary-color);
            border-radius: 4px;
            width: 0%;
            animation: progress 2s ease-in-out infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        @keyframes progress {
            0% { width: 0%; }
            50% { width: 100%; }
            100% { width: 0%; }
        }
        
        /* Estilos atualizados do footer */
        footer {
            background-color: var(--secondary-color);
            color: white;
            padding: 3rem 0 1.5rem;
        }
        
        .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }
        
        .footer-logo {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .footer-logo img {
            height: 40px;
            margin-right: 10px;
        }
        
        .footer-logo span {
            font-size: 1.25rem;
            font-weight: 600;
        }
        
        .footer-social {
            display: flex;
            gap: 15px;
        }
        
        .social-link {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            transition: all 0.3s ease;
        }
        
        .social-link:hover {
            background-color: var(--primary-color);
            color: white;
            transform: translateY(-3px);
        }
        
        .footer-bottom {
            text-align: center;
            padding-top: 1.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .section-card {
            margin-bottom: 2rem;
        }
    </style>
</head>
<body>
    <!-- Tela de Carregamento -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner"></div>
        <div class="loading-text">Processando seu arquivo...</div>
        <div class="loading-progress">
            <div class="loading-progress-bar"></div>
        </div>
    </div>

    <!-- Frame oculto para download -->
    <iframe id="downloadFrame" style="display:none;"></iframe>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <i class="fas fa-exchange-alt me-2" style="color: #ff9d40;"></i>
                <span class="fw-bold">Conversor Excel para SQL</span>
            </a>
        </div>
    </nav>

    <div class="container main-container">
        <div class="row">
            <!-- Formulário de Upload - Agora ocupando toda a largura -->
            <div class="col-12 section-card">
                <div class="card">
                    <div class="card-header text-white">
                        <h3 class="mb-0"><i class="fas fa-file-upload me-2"></i>Carregar Arquivo</h3>
                    </div>
                    <div class="card-body">
                        <?php if($success): ?>
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle me-2"></i> <?php echo $success; ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if($error): ?>
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-circle me-2"></i> <?php echo $error; ?>
                            </div>
                        <?php endif; ?>
                        
                        <form action="converter.php" method="post" enctype="multipart/form-data" id="uploadForm">
                            <div class="file-upload-wrapper mb-4">
                                <input type="file" class="file-upload-input" id="arquivo" name="arquivo" accept=".xlsx" required>
                                <div class="text-center">
                                    <i class="fas fa-file-excel fa-3x text-primary mb-3"></i>
                                    <h4 class="mb-2">Arraste e solte seu arquivo Excel aqui</h4>
                                    <p class="text-muted">Ou clique para selecionar</p>
                                    <div class="selected-file" id="selected-file-info">
                                        <i class="fas fa-file-alt me-2"></i>
                                        <span id="file-name">Nenhum arquivo selecionado</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary" id="submitButton">
                                    <i class="fas fa-exchange-alt me-2"></i>Converter para SQL
                                </button>
                            </div>
                        </form>
                        
                        <div class="alert alert-info mt-4">
                            <div class="d-flex">
                                <div class="me-3">
                                    <i class="fas fa-lightbulb fa-2x text-warning"></i>
                                </div>
                                <div>
                                    <h5 class="alert-heading">Dica importante</h5>
                                    <p class="mb-0">Certifique-se de que sua planilha tenha a primeira linha como cabeçalho contendo os nomes das colunas. Isso ajudará a criar um SQL com nomes de campos corretos.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informações do Projeto - Agora ocupando toda a largura -->
            <div class="col-12 section-card">
                <div class="card">
                    <div class="card-header text-white">
                        <h3 class="mb-0"><i class="fas fa-info-circle me-2"></i>Sobre o Conversor</h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <h4 class="text-primary mb-3">O que é isso?</h4>
                            <p>Esta ferramenta converte arquivos Excel (.xlsx) em comandos SQL para importação direta em bancos de dados MySQL. É uma solução rápida para transformar suas planilhas em dados estruturados no seu banco de dados.</p>
                        </div>
                        
                        <h4 class="text-primary mb-3">Como funciona</h4>
                        <div class="step-card">
                            <span class="step-number">1</span>
                            <span><strong>Carregue sua planilha</strong> - Selecione um arquivo Excel (.xlsx) do seu computador</span>
                        </div>
                        <div class="step-card">
                            <span class="step-number">2</span>
                            <span><strong>Processamento automático</strong> - O sistema extrai os dados e gera os comandos SQL</span>
                        </div>
                        <div class="step-card">
                            <span class="step-number">3</span>
                            <span><strong>Download do SQL</strong> - Obtenha o script SQL pronto para importação no MySQL</span>
                        </div>
                        
                        <h4 class="text-primary mt-4 mb-3">Recursos</h4>
                        <ul class="features-list">
                            <li><i class="fas fa-check-circle text-success me-2"></i> <strong>Conversão rápida</strong> - Transforme suas planilhas em segundos</li>
                            <li><i class="fas fa-check-circle text-success me-2"></i> <strong>Suporta múltiplas abas</strong> - Processa todas as abas do Excel</li>
                            <li><i class="fas fa-check-circle text-success me-2"></i> <strong>Preserva tipos de dados</strong> - Detecção automática de tipos</li>
                            <li><i class="fas fa-check-circle text-success me-2"></i> <strong>Compatível com MySQL</strong> - Scripts otimizados para MySQL</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            
        </div>
    </div>
    
    <!-- Footer Atualizado -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-logo">
                    <img src="assets/favicon/favicon.png" alt="Logo">
                    <span>Pedro Lazzaroni</span>
                </div>
                
                <div class="footer-social">
                    <a href="https://linkedin.com/in/pedrolazzaroni" class="social-link" target="_blank">
                        <i class="bi bi-linkedin"></i>
                    </a>
                    <a href="https://github.com/pedrolazzaroni" class="social-link" target="_blank">
                        <i class="bi bi-github"></i>
                    </a>
                    <a href="https://www.instagram.com/pedro_lazzaroni" class="social-link" target="_blank">
                        <i class="bi bi-instagram"></i>
                    </a>
                    <a href="mailto:contato@pedrolazzaroni.com.br" class="social-link">
                        <i class="bi bi-envelope"></i>
                    </a>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> Pedro Lazzaroni. Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('arquivo').addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name || 'Nenhum arquivo selecionado';
            document.getElementById('file-name').textContent = fileName;
            document.getElementById('selected-file-info').style.display = 'block';
        });
        
        // Script para exibir a tela de carregamento
        document.getElementById('uploadForm').addEventListener('submit', function(e) {
            e.preventDefault(); // Impede o envio tradicional do formulário
            
            // Verifica se um arquivo foi selecionado
            if (document.getElementById('arquivo').files.length > 0) {
                // Mostra a tela de carregamento
                document.getElementById('loadingOverlay').style.display = 'flex';
                
                // Desabilita o botão de envio para evitar múltiplos envios
                document.getElementById('submitButton').disabled = true;
                
                // Simula mensagens de progresso
                const loadingText = document.querySelector('.loading-text');
                const messages = [
                    "Processando seu arquivo...",
                    "Analisando conteúdo da planilha...",
                    "Convertendo dados para SQL...",
                    "Finalizando o processamento..."
                ];
                
                let messageIndex = 0;
                const messageInterval = setInterval(function() {
                    messageIndex = (messageIndex + 1) % messages.length;
                    loadingText.textContent = messages[messageIndex];
                }, 3000);
                
                // Preparar os dados do formulário para envio via AJAX
                const formData = new FormData(this);
                
                // Enviar o formulário via AJAX
                fetch('converter.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erro ao processar o arquivo');
                    }
                    
                    // Verificar se o Content-Type é um tipo de download
                    const contentType = response.headers.get('Content-Type');
                    const contentDisposition = response.headers.get('Content-Disposition');
                    
                    if (contentType === 'application/octet-stream' || 
                        (contentDisposition && contentDisposition.includes('attachment'))) {
                        
                        // É um download, obter o nome do arquivo
                        let filename = 'arquivo.sql';
                        if (contentDisposition) {
                            const filenameMatch = contentDisposition.match(/filename="(.+)"/);
                            if (filenameMatch && filenameMatch[1]) {
                                filename = filenameMatch[1];
                            }
                        }
                        
                        // Converter a resposta para blob e criar URL
                        return response.blob().then(blob => {
                            const url = window.URL.createObjectURL(blob);
                            
                            // Criar link de download e clicar automaticamente
                            const a = document.createElement('a');
                            a.style.display = 'none';
                            a.href = url;
                            a.download = filename;
                            document.body.appendChild(a);
                            
                            // Ocultar a tela de carregamento antes de iniciar o download
                            clearInterval(messageInterval);
                            document.getElementById('loadingOverlay').style.display = 'none';
                            
                            // Mostrar mensagem de sucesso
                            const alertDiv = document.createElement('div');
                            alertDiv.className = 'alert alert-success';
                            alertDiv.innerHTML = '<i class="fas fa-check-circle me-2"></i> Conversão concluída! O download está começando...';
                            const formElement = document.getElementById('uploadForm');
                            formElement.parentNode.insertBefore(alertDiv, formElement);
                            
                            // Iniciar o download
                            a.click();
                            window.URL.revokeObjectURL(url);
                            document.body.removeChild(a);
                            
                            // Reativar o botão de envio
                            document.getElementById('submitButton').disabled = false;
                            
                            return true;
                        });
                    } else {
                        // Se não for um download, é provavelmente uma mensagem de erro
                        return response.text();
                    }
                })
                .then(result => {
                    if (result !== true) {
                        // Se não foi um download bem-sucedido, exibir a mensagem de erro
                        throw new Error(result || 'Erro ao processar o arquivo');
                    }
                })
                .catch(error => {
                    // Ocultar a tela de carregamento
                    clearInterval(messageInterval);
                    document.getElementById('loadingOverlay').style.display = 'none';
                    
                    // Mostrar mensagem de erro
                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'alert alert-danger';
                    alertDiv.innerHTML = '<i class="fas fa-exclamation-circle me-2"></i> ' + error.message;
                    const formElement = document.getElementById('uploadForm');
                    formElement.parentNode.insertBefore(alertDiv, formElement);
                    
                    // Reativar o botão de envio
                    document.getElementById('submitButton').disabled = false;
                });
            }
        });
    </script>
</body>
</html>
