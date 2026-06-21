<?php
require_once 'db.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    header("Location: index.php");
    exit;
}

// Busca os detalhes do produto existente
try {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $product = $stmt->fetch();

    if (!$product) {
        // Produto não encontrado
        header("Location: index.php?status=error");
        exit;
    }
} catch (\PDOException $e) {
    die("Erro no banco de dados: " . htmlspecialchars($e->getMessage()));
}

$errors = [];
$name = $product['name'];
$description = $product['description'] ?: '';
$price = $product['price'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    $price = isset($_POST['price']) ? trim($_POST['price']) : '';

    // Validação
    if ($name === '') {
        $errors['name'] = 'Necessário digitar nome para o produto.';
    } elseif (strlen($name) > 255) {
        $errors['name'] = 'Nome do produto deve ter menos de 255 caracteres.';
    }

    if ($price === '') {
        $errors['price'] = 'Necessário atribuir um preço.';
    } elseif (!is_numeric($price)) {
        $errors['price'] = 'O preço deve ser um valor numérico.';
    } elseif (floatval($price) < 0) {
        $errors['price'] = 'O preço não pode ser um valor negativo.';
    }

    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("UPDATE products SET name = :name, description = :description, price = :price WHERE id = :id");
            $stmt->execute([
                'name' => $name,
                'description' => $description === '' ? null : $description,
                'price' => floatval($price),
                'id' => $id
            ]);
            header("Location: index.php?status=updated");
            exit;
        } catch (\PDOException $e) {
            $errors['global'] = 'Erro no banco de dados: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Produto - #<?= htmlspecialchars($product['id']) ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header>
        <div class="container navbar">
            <a href="index.php" class="logo">
                Estocador <span>Catálogo</span>
            </a>
            <div class="nav-links">
                <a href="index.php" class="btn btn-secondary">
                    Ver Catálogo
                </a>
            </div>
        </div>
    </header>

    <main class="container">
        <div class="panel" style="max-width: 650px; margin: 40px auto 0;">
            <div class="page-header">
                <div>
                    <a href="index.php" class="back-link">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                        Retornar ao Catálogo
                    </a>
                    <h1 class="page-title" style="margin-top: 10px;">Editar Produto #<?= htmlspecialchars($product['id']) ?></h1>
                </div>
            </div>

            <?php if (isset($errors['global'])): ?>
                <div class="alert alert-danger">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                    <?= htmlspecialchars($errors['global']) ?>
                </div>
            <?php endif; ?>

            <form action="edit.php?id=<?= $product['id'] ?>" method="POST">
                
                <div class="form-group">
                    <label for="name">Nome do Produto *</label>
                    <input type="text" id="name" name="name" class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>" placeholder="ex: Cadeira Gamer Ergonômica" value="<?= htmlspecialchars($name) ?>" required>
                    <?php if (isset($errors['name'])): ?>
                        <div style="color: #fb7185; font-size: 0.85rem; margin-top: 6px;"><?= $errors['name'] ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="price">Preço (R$ BRL) *</label>
                    <input type="number" id="price" name="price" step="0.01" min="0" class="form-control <?= isset($errors['price']) ? 'is-invalid' : '' ?>" placeholder="ex: 199,99" value="<?= htmlspecialchars($price) ?>" required>
                    <?php if (isset($errors['price'])): ?>
                        <div style="color: #fb7185; font-size: 0.85rem; margin-top: 6px;"><?= $errors['price'] ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="description">Descrição</label>
                    <textarea id="description" name="description" class="form-control" placeholder="Forneça uma descrição detalhada do produto..."><?= htmlspecialchars($description) ?></textarea>
                </div>

                <div class="form-actions">
                    <a href="index.php" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                        Salvar Alterações
                    </button>
                </div>

            </form>
        </div>
    </main>
</body>
</html>