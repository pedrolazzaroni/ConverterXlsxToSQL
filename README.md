# ExcelToSQL

Uma aplicação web moderna para converter arquivos Excel (.xlsx) em comandos SQL prontos para importação em qualquer banco de dados MySQL. O sistema detecta automaticamente os cabeçalhos do arquivo Excel e gera scripts SQL otimizados com interface intuitiva e responsiva.

## Características

- ✅ Conversão de Excel para SQL com um único clique
- ✅ Detecção automática dos cabeçalhos das colunas
- ✅ Suporte completo a caracteres especiais e acentos (UTF-8)
- ✅ Download automático do arquivo SQL gerado
- ✅ Interface moderna com tema escuro e animações
- ✅ Feedback visual durante o processamento
- ✅ Carregamento progressivo e animado das páginas
- ✅ Navegação suave entre seções da página
- ✅ Design responsivo para todos os dispositivos
- ✅ Upload por arrastar e soltar ou seleção direta
- ✅ Não requer configuração de banco de dados

## Interface e Experiência do Usuário

- 🎨 Tema escuro moderno para redução de fadiga visual
- 🔄 Animações de transição e carregamento
- 📱 Layout totalmente responsivo (desktop, tablet, mobile)
- 🖱️ Navegação intuitiva com rolagem suave
- 📋 Feedback em tempo real durante processamento
- 💾 Download automático após conversão

## Requisitos

- Servidor web (Apache, Nginx, etc.)
- PHP 7.4 ou superior
- Extensões PHP necessárias:
  - zip
  - xml
  - gd
  - mbstring
  - fileinfo
- Composer (gerenciador de dependências PHP)

## Instalação

1. Clone ou baixe este repositório para seu servidor web
2. Navegue até a pasta do projeto via terminal:
   ```
   cd /caminho/para/ConverterXlsxToSQL
   ```
3. Instale as dependências via Composer:
   ```
   composer install
   ```
4. Certifique-se de que a pasta tem permissões de escrita para o servidor web (necessário para processamento temporário dos arquivos)

## Como usar

1. Acesse a aplicação pelo navegador: 
   ```
   http://localhost/Laravel/ConverterXlsxToSQL/
   ```
   (ajuste o caminho conforme sua configuração)

2. Arraste e solte um arquivo Excel (.xlsx) na área indicada ou clique para selecionar um arquivo

3. Clique em "Converter para SQL"

4. Aguarde o processamento - uma tela de carregamento com feedback visual será exibida

5. O download do arquivo SQL iniciará automaticamente quando o processamento for concluído

6. Importe o arquivo SQL gerado em seu sistema de gerenciamento de banco de dados MySQL

## Funcionalidades Técnicas

### Estrutura do arquivo SQL gerado

O arquivo SQL gerado contém:

1. **Configurações de Charset**: Garantindo suporte adequado a caracteres UTF-8
   ```sql
   SET NAMES utf8mb4;
   ```

2. **Criação da Tabela**: Baseada no nome do arquivo Excel e colunas detectadas
   ```sql
   CREATE TABLE IF NOT EXISTS `nome_da_tabela` (
     `id` INT AUTO_INCREMENT PRIMARY KEY,
     `coluna1` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
     `coluna2` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
     ...
     `data_importacao` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
   ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
   ```

3. **Comandos INSERT**: Um comando para cada linha de dados do Excel
   ```sql
   INSERT INTO `nome_da_tabela` (`coluna1`, `coluna2`, ...) VALUES ('valor1', 'valor2', ...);
   ```

## Tecnologias Utilizadas

- **Frontend**: HTML5, CSS3, JavaScript, Bootstrap 5
- **Backend**: PHP
- **Dependências**: PhpSpreadsheet (processamento de arquivos Excel)
- **Ícones**: Font Awesome, Bootstrap Icons

## Dicas de uso

- Certifique-se de que a primeira linha do seu arquivo Excel contém os nomes das colunas
- Para arquivos muito grandes, o processamento pode levar mais tempo
- O nome da tabela SQL será derivado do nome do arquivo Excel
- Todas as colunas são criadas como TEXT por padrão - você pode alterar manualmente o tipo de dados após a importação, se necessário

## Solução de problemas

- **Erro "Arquivo muito grande"**: Aumente os limites de upload em seu php.ini (`upload_max_filesize` e `post_max_size`)
- **Tempo limite excedido**: Aumente o `max_execution_time` no php.ini
- **Caracteres incorretos**: Verifique se seu sistema está configurado para UTF-8
- **Problemas de visualização**: Certifique-se de usar um navegador atualizado (Chrome, Firefox, Edge, Safari)

## Licença

Copyright © Pedro Lazzaroni
