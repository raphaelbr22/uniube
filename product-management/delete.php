<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

    if ($id > 0) {
        try {
            $stmt = $pdo->prepare("DELETE FROM products WHERE id = :id");
            $stmt->execute(['id' => $id]);
            
            header("Location: index.php?status=deleted");
            exit;
        } catch (\PDOException $e) {
            header("Location: index.php?status=error");
            exit;
        }
    }
}

// Redireciona de volta se não for uma requisição POST válida
header("Location: index.php");
exit;