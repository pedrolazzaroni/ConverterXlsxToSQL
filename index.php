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
    <title>Conversor Excel para MySQL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">Conversor de Excel para MySQL</h3>
                    </div>
                    <div class="card-body">
                        <?php if($success): ?>
                            <div class="alert alert-success">
                                <?php echo $success; ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if($error): ?>
                            <div class="alert alert-danger">
                                <?php echo $error; ?>
                            </div>
                        <?php endif; ?>
                        
                        <form action="converter.php" method="post" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="arquivo" class="form-label">Selecione o arquivo Excel (.xlsx)</label>
                                <input type="file" class="form-control" id="arquivo" name="arquivo" accept=".xlsx" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Converter para MySQL</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
