<?php
require_once 'db.php';

// Handle Search Query
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

if ($search !== '') {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE name LIKE :search OR description LIKE :search ORDER BY id DESC");
    $stmt->execute(['search' => '%' . $search . '%']);
} else {
    $stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
}
$products = $stmt->fetchAll();

// Fetch metrics
$total_stmt = $pdo->query("SELECT COUNT(*) as total, COALESCE(AVG(price), 0) as avg_price, COALESCE(MAX(price), 0) as max_price FROM products");
$metrics = $total_stmt->fetch();

// Check for status messages
$status = isset($_GET['status']) ? $_GET['status'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Catalog - Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header>
        <div class="container navbar">
            <a href="index.php" class="logo">
                Antigravity <span>Catalog</span>
            </a>
            <div class="nav-links">
                <a href="create.php" class="btn btn-primary">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    Add Product
                </a>
            </div>
        </div>
    </header>

    <main class="container">
        
        <!-- Status Messages -->
        <?php if ($status === 'created'): ?>
            <div class="alert alert-success">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                Product created successfully!
            </div>
        <?php elseif ($status === 'updated'): ?>
            <div class="alert alert-success">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                Product updated successfully!
            </div>
        <?php elseif ($status === 'deleted'): ?>
            <div class="alert alert-success">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                Product deleted successfully.
            </div>
        <?php elseif ($status === 'error'): ?>
            <div class="alert alert-danger">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                An error occurred. Please try again.
            </div>
        <?php endif; ?>

        <!-- Catalog Overview Metrics -->
        <section class="metrics">
            <div class="metric-card">
                <span class="metric-label">Total Products</span>
                <span class="metric-value"><?= number_format($metrics['total']) ?></span>
            </div>
            <div class="metric-card">
                <span class="metric-label">Average Price</span>
                <span class="metric-value">$<?= number_format($metrics['avg_price'], 2) ?></span>
            </div>
            <div class="metric-card">
                <span class="metric-label">Highest Value</span>
                <span class="metric-value">$<?= number_format($metrics['max_price'], 2) ?></span>
            </div>
        </section>

        <!-- Product Table Area -->
        <div class="panel">
            <div class="page-header">
                <h1 class="page-title">Catalog Inventory</h1>
                
                <!-- Search Form -->
                <form action="index.php" method="GET" style="display: flex; gap: 10px; max-width: 400px; width: 100%;">
                    <input type="text" name="search" class="form-control" placeholder="Search by name or description..." value="<?= htmlspecialchars($search) ?>">
                    <button type="submit" class="btn btn-secondary">Search</button>
                    <?php if ($search !== ''): ?>
                        <a href="index.php" class="btn btn-secondary" title="Clear Search">Reset</a>
                    <?php endif; ?>
                </form>
            </div>

            <?php if (empty($products)): ?>
                <div class="empty-state">
                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="8" y1="12" x2="16" y2="12"></line>
                    </svg>
                    <p>No products found in the catalog.</p>
                    <?php if ($search !== ''): ?>
                        <a href="index.php" class="btn btn-secondary">Clear Search Filter</a>
                    <?php else: ?>
                        <a href="create.php" class="btn btn-primary">Add Your First Product</a>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th style="width: 80px;">ID</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th style="width: 150px; text-align: right;">Price</th>
                                <th style="width: 200px; text-align: center;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products as $product): ?>
                                <tr>
                                    <td>#<?= htmlspecialchars($product['id']) ?></td>
                                    <td style="font-weight: 600; color: #fff;"><?= htmlspecialchars($product['name']) ?></td>
                                    <td style="color: var(--text-secondary); max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                        <?= htmlspecialchars($product['description'] ?: 'No description provided.') ?>
                                    </td>
                                    <td class="price-tag" style="text-align: right;">
                                        $<?= number_format($product['price'], 2) ?>
                                    </td>
                                    <td>
                                        <div class="actions-cell" style="justify-content: center;">
                                            <a href="edit.php?id=<?= $product['id'] ?>" class="btn btn-secondary btn-sm" title="Edit Product">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 1 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                                Edit
                                            </a>
                                            
                                            <!-- Delete using post form for CSRF safety and clean security controls -->
                                            <form action="delete.php" method="POST" onsubmit="return confirm('Are you sure you want to delete \'<?= htmlspecialchars($product['name'], ENT_QUOTES) ?>\'? This action cannot be undone.');" style="display: inline;">
                                                <input type="hidden" name="id" value="<?= $product['id'] ?>">
                                                <button type="submit" class="btn btn-danger btn-sm" title="Delete Product">
                                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                                    Delete
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

    <footer class="container">
        <p>&copy; <?= date('Y') ?> Antigravity Product Management Catalog. All rights reserved.</p>
    </footer>

</body>
</html>
