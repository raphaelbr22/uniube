<?php
require_once 'db.php';

// Trata a Consulta de Busca
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

if ($search !== '') {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE name LIKE :search OR description LIKE :search ORDER BY id DESC");
    $stmt->execute(['search' => '%' . $search . '%']);
} else {
    $stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
}
$products = $stmt->fetchAll();

// Busca as métricas
$total_stmt = $pdo->query("SELECT COUNT(*) as total, COALESCE(AVG(price), 0) as avg_price, COALESCE(MAX(price), 0) as max_price FROM products");
$metrics = $total_stmt->fetch();

// Verifica mensagens de status
$status = isset($_GET['status']) ? $_GET['status'] : '';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo de Produtos - Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header>
        <div class="container navbar">
            <a href="index.php" class="logo">
                Estocador <span>Catálogo</span>
            </a>
            <div class="nav-links">
                <a href="create.php" class="btn btn-primary">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    Adicionar Produto
                </a>
            </div>
        </div>
    </header>

    <main class="container">
        
        <?php if ($status === 'created'): ?>
            <div class="alert alert-success">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                Produto criado com successo!
            </div>
        <?php elseif ($status === 'updated'): ?>
            <div class="alert alert-success">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                Produto atualizado com successo!
            </div>
        <?php elseif ($status === 'deleted'): ?>
            <div class="alert alert-success">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                Produto excluído com successo.
            </div>
        <?php elseif ($status === 'error'): ?>
            <div class="alert alert-danger">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                Detectamos um erro. Por favor, tente novamente.
            </div>
        <?php endif; ?>

        <section class="metrics">
            <div class="metric-card">
                <span class="metric-label">Total de Produtos</span>
                <span class="metric-value"><?= number_format($metrics['total']) ?></span>
            </div>
            <div class="metric-card">
                <span class="metric-label">Preço Médio</span>
                <span class="metric-value">R$ <?= number_format($metrics['avg_price'], 2, ',', '.') ?></span>
            </div>
            <div class="metric-card">
                <span class="metric-label">Maior Valor</span>
                <span class="metric-value">R$ <?= number_format($metrics['max_price'], 2, ',', '.') ?></span>
            </div>
        </section>

        <div class="panel">
            <div class="page-header">
                <h1 class="page-title">Catálogo</h1>
                
                <form action="index.php" method="GET" style="display: flex; gap: 10px; max-width: 400px; width: 100%;">
                    <input type="text" name="search" class="form-control" placeholder="Buscar por nome ou descrição..." value="<?= htmlspecialchars($search) ?>">
                    <button type="submit" class="btn btn-secondary">Buscar</button>
                    <?php if ($search !== ''): ?>
                        <a href="index.php" class="btn btn-secondary" title="Limpar Busca">Resetar Busca</a>
                    <?php endif; ?>
                </form>
            </div>

            <?php if (empty($products)): ?>
                <div class="empty-state">
                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="8" y1="12" x2="16" y2="12"></line>
                    </svg>
                    <p>Nenhum Produto Encontrado no Catálogo.</p>
                    <?php if ($search !== ''): ?>
                        <a href="index.php" class="btn btn-secondary">Limpar Filtro de Busca</a>
                    <?php else: ?>
                        <a href="create.php" class="btn btn-primary">Adicionar seu Primeiro Produto</a>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th style="width: 80px;">ID</th>
                                <th>Nome</th>
                                <th>Descrição</th>
                                <th style="width: 150px; text-align: right;">Preço</th>
                                <th style="width: 200px; text-align: center;">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products as $product): ?>
                                <tr>
                                    <td>#<?= htmlspecialchars($product['id']) ?></td>
                                    <td style="font-weight: 600; color: #fff;"><?= htmlspecialchars($product['name']) ?></td>
                                    <td style="color: var(--text-secondary); max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                        <?= htmlspecialchars($product['description'] ?: 'Nenhuma descrição fornecida.') ?>
                                    </td>
                                    <td class="price-tag" style="text-align: right;">
                                        R$ <?= number_format($product['price'], 2, ',', '.') ?>
                                    </td>
                                    <td>
                                        <div class="actions-cell" style="justify-content: center;">
                                            <a href="edit.php?id=<?= $product['id'] ?>" class="btn btn-secondary btn-sm" title="Editar Produto">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 1 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                                Editar
                                            </a>
                                            
                                            <form action="delete.php" method="POST" onsubmit="return confirm('Tem certeza que deseja deletar \'<?= htmlspecialchars($product['name'], ENT_QUOTES) ?>\'? Esta ação não poderá ser desfeita.');" style="display: inline;">
                                                <input type="hidden" name="id" value="<?= $product['id'] ?>">
                                                <button type="submit" class="btn btn-danger btn-sm" title="Deletar Produto">
                                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                                    Deletar
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>