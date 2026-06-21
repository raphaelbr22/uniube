<?php
/**
 * Conexão com o banco de dados usando PDO.
 */

$host = '127.0.0.1';
$db   = 'product_db';
$user = 'root';
$pass = ''; // A senha padrão do XAMPP é vazia
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // Para desenvolvimento local, exibe uma erro amigável para o usuário e também uma mensagem detalhada.
    die("<div style='font-family: sans-serif; padding: 20px; border: 1px solid #ff5e5e; background-color: #fff0f0; border-radius: 8px; max-width: 600px; margin: 40px auto;'>
            <h3 style='color: #d93838; margin-top: 0;'>Falha na Conexão com o Banco de Dados</h3>
            <p>Não foi possível conectar ao servidor de banco de dados. Certifique-se de que o serviço MySQL do XAMPP está em execução.</p>
            <p style='font-size: 0.85em; color: #666;'><strong>Detalhes do erro:</strong> " . htmlspecialchars($e->getMessage()) . "</p>
         </div>");
}