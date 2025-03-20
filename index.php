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
    <title>Excel para SQL | Conversor Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="shortcut icon" href="./assets/favicon/favicon.png" type="image/x-icon">
    <style>
        :root {
            --primary-color: #ff7700;
            --secondary-color: #111111;
            --text-color: #f5f5f5;
            --bg-color: #121212;
            --accent-color: #ff9d40;
            --section-bg: rgba(255, 255, 255, 0.05);
            --border-color: rgba(255, 255, 255, 0.1);
        }
        
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        
        body {
            background-color: var(--bg-color);
            color: var(--text-color);
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            overflow-x: hidden;
        }
        
        .page-container {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        
        header {
            background-color: var(--secondary-color);
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            border-bottom: 1px solid var(--border-color);
            transition: all 0.3s ease;
        }
        
        /* Nova classe para o efeito de blur no header ao rolar */
        header.scrolled {
            background-color: rgba(17, 17, 17, 0.8);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }
        
        /* Botão de menu mobile e sua animação */
        .mobile-menu-toggle {
            display: none;
            flex-direction: column;
            justify-content: space-between;
            height: 24px;
            width: 30px;
            cursor: pointer;
            z-index: 1001;
        }
        
        .mobile-menu-toggle span {
            display: block;
            height: 3px;
            width: 100%;
            background-color: var(--text-color);
            border-radius: 3px;
            transition: all 0.3s ease;
        }
        
        .mobile-menu-toggle.active span:nth-child(1) {
            transform: translateY(10px) rotate(45deg);
        }
        
        .mobile-menu-toggle.active span:nth-child(2) {
            opacity: 0;
        }
        
        .mobile-menu-toggle.active span:nth-child(3) {
            transform: translateY(-10px) rotate(-45deg);
        }
        
        .header-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.5rem 0;
        }
        
        .logo {
            display: flex;
            align-items: center;
            color: var(--text-color);
            text-decoration: none;
        }
        
        .logo i {
            color: var(--primary-color);
            font-size: 1.5rem;
            margin-right: 0.75rem;
        }
        
        .logo span {
            font-size: 1.25rem;
            font-weight: 600;
        }
        
        main {
            flex: 1;
            padding: 2rem 0;
        }
        
        .hero-section {
            padding: 2rem 0;
            position: relative;
            border-top: none;
        }
        
        .hero-section::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: -1;
        }
        
        .hero-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--text-color);
        }
        
        .hero-description {
            font-size: 1.25rem;
            margin-bottom: 2rem;
            max-width: 800px;
            color: rgba(255, 255, 255, 0.8);
        }
        
        .section {
            padding: 3rem 0;
            border-top: 1px solid var(--border-color);
        }
        
        .section-title {
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            font-weight: 600;
            display: flex;
            align-items: center;
        }
        
        .section-title i {
            margin-right: 0.75rem;
        }
        
        .upload-container {
            background-color: var(--section-bg);
            border-radius: 16px;
            padding: 2rem;
            border: 1px solid var(--border-color);
            position: relative;
            overflow: hidden;
            height: 100%;
            min-height: 400px;
            display: flex;
            flex-direction: column;
        }
        
        .upload-container::before {
            content: "";
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle at center, rgba(255, 119, 0, 0.05), transparent 70%);
            z-index: -1;
        }
        
        .file-upload-wrapper {
            border: 2px dashed var(--primary-color);
            border-radius: 12px;
            padding: 3rem 2rem;
            text-align: center;
            transition: all 0.3s ease;
            background-color: rgba(255, 255, 255, 0.03);
            margin-bottom: 1.5rem;
        }
        
        .file-upload-wrapper:hover {
            background-color: rgba(255, 255, 255, 0.05);
            border-color: var(--accent-color);
            transform: translateY(-5px);
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
        
        .file-icon {
            font-size: 3rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }
        
        .selected-file {
            display: none;
            margin-top: 1rem;
            padding: 0.75rem 1rem;
            background-color: rgba(255, 119, 0, 0.1);
            border-radius: 8px;
            border-left: 3px solid var(--primary-color);
        }
        
        .btn-convert {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            width: 100%;
            position: relative;
            overflow: hidden;
            z-index: 1;
        }
        
        .btn-convert::before {
            content: "";
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: all 0.5s ease;
            z-index: -1;
        }
        
        .btn-convert:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(255, 119, 0, 0.3);
        }
        
        .btn-convert:hover::before {
            left: 100%;
        }
        
        .btn-convert i {
            margin-right: 0.5rem;
        }
        
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }
        
        .feature-card {
            background-color: var(--section-bg);
            border-radius: 12px;
            padding: 1.5rem;
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
            border-color: var(--primary-color);
        }
        
        .feature-icon {
            color: var(--primary-color);
            font-size: 2rem;
            margin-bottom: 1rem;
        }
        
        .feature-title {
            font-weight: 600;
            margin-bottom: 0.75rem;
            color: var(--text-color);
        }
        
        .feature-desc {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.95rem;
        }
        
        .steps-container {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            margin: 2rem 0;
        }
        
        .step-item {
            display: flex;
            background-color: var(--section-bg);
            border-radius: 12px;
            padding: 1.5rem;
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
        }
        
        .step-item:hover {
            transform: translateX(5px);
            border-color: var(--primary-color);
        }
        
        .step-number {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background-color: var(--primary-color);
            color: white;
            border-radius: 50%;
            font-weight: bold;
            margin-right: 1rem;
            flex-shrink: 0;
        }
        
        .step-content {
            flex: 1;
        }
        
        .step-title {
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--text-color);
        }
        
        .step-desc {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.95rem;
        }
        
        .alert {
            border-radius: 10px;
            border: none;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
            position: relative;
            border-left: 4px solid transparent;
        }
        
        .alert-success {
            background-color: rgba(40, 167, 69, 0.1);
            color: #2ecc71;
            border-left-color: #2ecc71;
        }
        
        .alert-danger {
            background-color: rgba(220, 53, 69, 0.1);
            color: #ff5252;
            border-left-color: #ff5252;
        }
        
        .alert-info {
            background-color: rgba(255, 119, 0, 0.1);
            color: var(--accent-color);
            border-left-color: var(--primary-color);
        }
        
        /* Estilos da tela de carregamento */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            display: none;
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }
        
        .loading-spinner {
            width: 80px;
            height: 80px;
            border: 4px solid rgba(255, 255, 255, 0.1);
            border-top: 4px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-bottom: 1.5rem;
        }
        
        .loading-text {
            color: white;
            font-size: 1.5rem;
            font-weight: 600;
            text-align: center;
            margin-bottom: 1rem;
        }
        
        .loading-progress {
            width: 300px;
            height: 6px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
            overflow: hidden;
            position: relative;
        }
        
        .loading-progress-bar {
            height: 100%;
            background-color: var(--primary-color);
            border-radius: 4px;
            width: 100%;
            position: absolute;
            animation: progress-animation 1.5s ease-in-out infinite;
        }
        
        @keyframes progress-animation {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }
        
        /* Estilos para os atalhos no header */
        .nav-links {
            display: flex;
            gap: 20px;
        }
        
        .nav-link {
            color: var(--text-color);
            text-decoration: none;
            font-weight: 500;
            position: relative;
            padding: 5px 0;
            transition: color 0.3s ease;
        }
        
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background-color: var(--primary-color);
            transition: width 0.3s ease;
        }
        
        .nav-link:hover {
            color: var(--primary-color);
        }
        
        .nav-link:hover::after {
            width: 100%;
        }
        
        /* Efeito de carregamento da página */
        .page-loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: var(--secondary-color);
            z-index: 10000;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            transition: opacity 0.8s ease, visibility 0.8s ease;
        }
        
        .loader-content {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        
        .loader-icon {
            font-size: 4rem;
            color: var(--primary-color);
            margin-bottom: 20px;
            animation: pulse 1.5s infinite alternate;
        }
        
        .loader-text {
            font-size: 1.5rem;
            color: var(--text-color);
            text-align: center;
            max-width: 400px;
            opacity: 0;
            animation: fadeIn 0.8s forwards 0.5s;
        }
        
        .loader-progress {
            width: 300px;
            height: 4px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
            margin-top: 30px;
            overflow: hidden;
        }
        
        .loader-progress-bar {
            height: 100%;
            width: 0;
            background-color: var(--primary-color);
            animation: loadProgress 2.5s ease forwards;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); opacity: 0.8; }
            100% { transform: scale(1.1); opacity: 1; }
        }
        
        @keyframes fadeIn {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }
        
        @keyframes loadProgress {
            0% { width: 0; }
            50% { width: 70%; }
            100% { width: 100%; }
        }
        
        /* Para rolagem suave */
        html {
            scroll-behavior: smooth;
        }
        
        footer {
            background-color: var(--secondary-color);
            color: white;
            padding: 3rem 0 1.5rem;
            border-top: 1px solid var(--border-color);
        }
        
        .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1.5rem;
        }
        
        .footer-logo {
            display: flex;
            align-items: center;
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
            background-color: rgba(255, 255, 255, 0.05);
            color: white;
            transition: all 0.3s ease;
            text-decoration: none;
        }
        
        .social-link:hover {
            background-color: var(--primary-color);
            color: white;
            transform: translateY(-3px);
        }
        
        .footer-bottom {
            text-align: center;
            padding-top: 1.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            color: rgba(255, 255, 255, 0.6);
        }
        
        /* Ajuste responsivo para os links do menu */
        @media (max-width: 768px) {
            .mobile-menu-toggle {
                display: flex;
            }
            
            .nav-links {
                position: fixed;
                top: 0;
                right: -100%;
                width: 70%;
                max-width: 300px;
                height: 100vh;
                background-color: var(--secondary-color);
                flex-direction: column;
                justify-content: center;
                align-items: center;
                gap: 2rem;
                transition: right 0.3s ease;
                z-index: 1000;
                padding: 2rem;
                box-shadow: -5px 0 15px rgba(0, 0, 0, 0.3);
            }
            
            .nav-links.active {
                right: 0;
            }
            
            .nav-link {
                font-size: 1.2rem;
                padding: 1rem 0;
                width: 100%;
                text-align: center;
            }
            
            .nav-link::after {
                bottom: -5px;
                height: 3px;
            }
            
            /* Overlay escuro quando o menu está aberto */
            .menu-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 999;
                display: none;
                backdrop-filter: blur(3px);
            }
            
            .menu-overlay.active {
                display: block;
            }
            
            .nav-links {
                gap: 10px;
                font-size: 0.9rem;
            }
            
            .hero-title {
                font-size: 2rem;
            }
            
            .hero-description {
                font-size: 1rem;
            }
            
            .upload-container {
                padding: 1.5rem;
            }
            
            .footer-content {
                flex-direction: column;
                align-items: center;
                text-align: center;
                gap: 1.5rem;
            }
        }
        
        @media (max-width: 991px) {
            .hero-section {
                padding: 2rem 0;
                text-align: center;
                margin-bottom: 2rem;
            }
            
            .hero-description {
                margin-left: auto;
                margin-right: auto;
            }
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        @keyframes progress {
            0% { left: -100%; }
            100% { left: 100%; }
        }
    </style>
</head>
<body>
    <!-- Efeito de carregamento da página -->
    <div class="page-loader" id="pageLoader">
        <div class="loader-content">
            <div class="loader-icon">
                <i class="fas fa-file-excel"></i>
            </div>
            <div class="loader-text">Preparando seu conversor Excel para SQL...</div>
            <div class="loader-progress">
                <div class="loader-progress-bar"></div>
            </div>
        </div>
    </div>

    <div class="page-container">
        <!-- Menu Overlay para versão mobile -->
        <div class="menu-overlay" id="menuOverlay"></div>
        
        <!-- Tela de Carregamento para o processamento -->
        <div class="loading-overlay" id="loadingOverlay">
            <div class="loading-spinner"></div>
            <div class="loading-text">Processando seu arquivo...</div>
            <div class="loading-progress">
                <div class="loading-progress-bar"></div>
            </div>
        </div>

        <!-- Header com atalhos -->
        <header id="mainHeader">
            <div class="container">
                <div class="header-content">
                    <a href="#top" class="logo">
                        <i class="fas fa-exchange-alt"></i>
                        <span style="font-style: italic;">ExcelToSQL</span>
                    </a>
                    
                    <!-- Botão do menu mobile -->
                    <div class="mobile-menu-toggle" id="mobileMenuToggle">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                    
                    <!-- Atalhos de navegação -->
                    <div class="nav-links" id="navLinks">
                        <a href="#top" class="nav-link">Início</a>
                        <a href="#how-it-works" class="nav-link">Como Funciona</a>
                        <a href="#features" class="nav-link">Recursos</a>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main id="top">
            <div class="container">
                <!-- Hero Section e Upload juntos - Ajustado para 20px do topo -->
                <div class="row align-items-center" style="margin-top: 20px;">
                    <!-- Hero Section (lado esquerdo) -->
                    <div class="col-lg-6 hero-section pe-lg-5">
                        <h1 class="hero-title">Converta Excel para SQL em segundos</h1>
                        <p class="hero-description">
                            Transforme suas planilhas Excel em comandos SQL prontos para importação em bancos de dados MySQL. 
                            Sem complicações, sem configurações. Apenas escolha seu arquivo e pronto!
                        </p>
                    </div>
                    
                    <!-- Upload Section (lado direito) -->
                    <div class="col-lg-6">
                        <div class="upload-container">
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
                                <div class="file-upload-wrapper position-relative">
                                    <input type="file" class="file-upload-input" id="arquivo" name="arquivo" accept=".xlsx" required>
                                    <div class="text-center">
                                        <div class="file-icon">
                                            <i class="fas fa-file-excel"></i>
                                        </div>
                                        <h4 class="mb-2">Arraste e solte seu arquivo Excel aqui</h4>
                                        <p>Ou clique para selecionar</p>
                                        <div class="selected-file" id="selected-file-info">
                                            <i class="fas fa-file-alt me-2"></i>
                                            <span id="file-name">Nenhum arquivo selecionado</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <button type="submit" class="btn btn-convert" id="submitButton">
                                    <i class="fas fa-exchange-alt"></i>Converter para SQL
                                </button>
                            </form>
                            
                            <div class="alert alert-info mt-4">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-lightbulb me-3" style="font-size: 1.5rem;"></i>
                                    <div>
                                        <h5 class="m-0">Dica importante</h5>
                                        <p class="mb-0 mt-1">Certifique-se de que sua planilha tenha a primeira linha como cabeçalho contendo os nomes das colunas.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- How It Works Section -->
                <section id="how-it-works" class="section" style="margin-top: 20px;">
                    <h2 class="section-title"><i class="fas fa-info-circle"></i> Como Funciona</h2>
                    
                    <div class="steps-container">
                        <div class="step-item">
                            <div class="step-number">1</div>
                            <div class="step-content">
                                <h3 class="step-title">Carregue sua planilha</h3>
                                <p class="step-desc">Selecione qualquer arquivo Excel (.xlsx) do seu computador e faça o upload.</p>
                            </div>
                        </div>
                        <div class="step-item">
                            <div class="step-number">2</div>
                            <div class="step-content">
                                <h3 class="step-title">Processamento automático</h3>
                                <p class="step-desc">O sistema identifica as colunas e converte todo o conteúdo para comandos SQL.</p>
                            </div>
                        </div>
                        <div class="step-item">
                            <div class="step-number">3</div>
                            <div class="step-content">
                                <h3 class="step-title">Download do SQL</h3>
                                <p class="step-desc">Receba instantaneamente um arquivo SQL pronto para importação no seu banco de dados.</p>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Features Section -->
                <section id="features" class="section">
                    <h2 class="section-title"><i class="fas fa-star"></i> Recursos</h2>
                    
                    <div class="features-grid">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="fas fa-bolt"></i>
                            </div>
                            <h3 class="feature-title">Conversão rápida</h3>
                            <p class="feature-desc">Transforme suas planilhas em segundos, sem esperas desnecessárias.</p>
                        </div>
                        
                        <!-- Novo recurso sobre facilidade de uso -->
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="fas fa-magic"></i>
                            </div>
                            <h3 class="feature-title">Sem complicações</h3>
                            <p class="feature-desc">Interface intuitiva que não requer conhecimentos técnicos. Apenas arraste, solte e pronto!</p>
                        </div>
                        
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="fas fa-language"></i>
                            </div>
                            <h3 class="feature-title">Suporte a acentos</h3>
                            <p class="feature-desc">Preserva acentos e caracteres especiais com codificação UTF-8 completa.</p>
                        </div>
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="fas fa-database"></i>
                            </div>
                            <h3 class="feature-title">Compatível com MySQL</h3>
                            <p class="feature-desc">Scripts otimizados para importação direta em bancos de dados MySQL.</p>
                        </div>
                    </div>
                </section>
            </div>
        </main>

        <!-- Footer -->
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
                        <!-- Novo ícone para acessar o portfólio -->
                        <a href="https://pedrolazzaroni.com.br" class="social-link" target="_blank">
                            <i class="bi bi-person-workspace"></i>
                        </a>
                    </div>
                </div>
                
                <div class="footer-bottom">
                    <p>&copy; <?php echo date('Y'); ?> Pedro Lazzaroni. Todos os direitos reservados.</p>
                </div>
            </div>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Código para o efeito de carregamento da página
        document.addEventListener('DOMContentLoaded', function() {
            // Simulação de carregamento da página
            setTimeout(function() {
                const pageLoader = document.getElementById('pageLoader');
                pageLoader.style.opacity = '0';
                pageLoader.style.visibility = 'hidden';
                
                // Revelar o conteúdo com uma ligeira animação
                document.body.style.animation = 'fadeIn 0.5s ease forwards';
            }, 2700); // Tempo total para o efeito de carregamento (um pouco mais que a animação da barra de progresso)
        });
        
        // Código para o file uploader e tela de carregamento de processamento
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
                const loadingOverlay = document.getElementById('loadingOverlay');
                loadingOverlay.style.display = 'flex';
                
                // Reinicia a animação da barra de progresso
                const progressBar = loadingOverlay.querySelector('.loading-progress-bar');
                progressBar.style.animation = 'none';
                void progressBar.offsetWidth; // Força o reflow
                progressBar.style.animation = 'progress-animation 1.5s ease-in-out infinite';
                
                // Desabilita o botão de envio para evitar múltiplos envios
                document.getElementById('submitButton').disabled = true;
                
                // Simula mensagens de progresso
                const loadingText = loadingOverlay.querySelector('.loading-text');
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
        
        // Código para o efeito de blur no header ao rolar
        window.addEventListener('scroll', function() {
            const header = document.getElementById('mainHeader');
            if (window.scrollY > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });
        
        // Código para o menu mobile
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuToggle = document.getElementById('mobileMenuToggle');
            const navLinks = document.getElementById('navLinks');
            const menuOverlay = document.getElementById('menuOverlay');
            const navLinksItems = document.querySelectorAll('.nav-link');
            
            // Função para abrir/fechar o menu
            function toggleMenu() {
                mobileMenuToggle.classList.toggle('active');
                navLinks.classList.toggle('active');
                menuOverlay.classList.toggle('active');
                document.body.classList.toggle('no-scroll');
            }
            
            // Toggle no clique do botão
            mobileMenuToggle.addEventListener('click', toggleMenu);
            
            // Fechar menu ao clicar no overlay
            menuOverlay.addEventListener('click', toggleMenu);
            
            // Fechar menu ao clicar em um link
            navLinksItems.forEach(link => {
                link.addEventListener('click', function() {
                    if (navLinks.classList.contains('active')) {
                        toggleMenu();
                    }
                });
            });
        });
    </script>
</body>
</html>
