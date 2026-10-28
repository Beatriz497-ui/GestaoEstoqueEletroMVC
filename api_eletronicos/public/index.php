<?php

// 1. Inclui os ficheiros das classes apontando para a pasta 'app'
require_once __DIR__ . '/../app/Models/Eletronico.php';
require_once __DIR__ . '/../app/Controllers/EletronicoController.php';
require_once __DIR__ . '/../app/Views/JsonView.php';

use App\Models\Eletronico;
use App\Controllers\EletronicoController;

// 2. Conexão PDO com o Banco de Dados gestao_estoque_eletro[cite: 2]
$host = 'localhost';
$dbname = 'gestao_estoque_eletro';
$username = 'root';
$password = '12345678'; 

try {
    $db = new PDO("mysql:host={$host};dbname={$dbname};charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["status" => "erro", "mensagem" => "Erro de conexão com o banco: " . $e->getMessage()]);
    exit;
}

// 3. Instancia o Model[cite: 3]
$eletronicoModel = new Eletronico($db);

// 4. Captura o Método e trata a URI[cite: 3]
$metodo = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Remove tudo o que estiver antes de /public na URL para não dar erro com subpastas[cite: 2, 4]
if (($pos = strpos($uri, '/public')) !== false) {
    $uri = substr($uri, $pos + strlen('/public'));
}

if (empty($uri)) {
    $uri = '/';
}

// ==========================================
// ROTAS
// ==========================================

// Rota 1: GET /eletronico[cite: 1]
if ($metodo === 'GET' && $uri === '/eletronico') {
    $controller = new EletronicoController($eletronicoModel);
    $controller->listar();
    exit;
}

// Resposta para rotas não encontradas (404)[cite: 2, 3]
http_response_code(404);
echo json_encode(["status" => "erro", "mensagem" => "Rota não encontrada."]);