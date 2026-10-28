<?php

// 1. Inclusão manual das classes (sem Composer)
require_once __DIR__ . '/../app/Models/Eletronico.php';
require_once __DIR__ . '/../app/Controllers/EletronicoController.php';
require_once __DIR__ . '/../app/Views/JsonView.php';

use App\Models\Eletronico;
use App\Controllers\EletronicoController;

// 2. Conexão PDO com a base de dados
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

// 3. Instancia o Model principal
$eletronicoModel = new Eletronico($db);

// 4. Captura e normaliza o Método e a URI da requisição
$metodo = strtoupper(trim($_SERVER['REQUEST_METHOD']));
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Remove o caminho relativo até a pasta /public
if (($pos = strpos($uri, '/public')) !== false) {
    $uri = substr($uri, $pos + strlen('/public'));
}

if (empty($uri)) {
    $uri = '/';
}

// ==========================================
// ROTAS DA API
// ==========================================

// Rota 1: GET /eletronico (Listar eletrónicos)
if ($metodo === 'GET' && $uri === '/eletronico') {
    $controller = new EletronicoController($eletronicoModel);
    $controller->listar();
    exit;
}

// Rota 2: POST /eletronico (Cadastrar eletrónico)
if ($metodo === 'POST' && $uri === '/eletronico') {
    $controller = new EletronicoController($eletronicoModel);
    $controller->cadastrar();
    exit;
}

// Rota 3: PUT /eletronico (Atualizar eletrónico)
if ($metodo === 'PUT' && $uri === '/eletronico') {
    $controller = new EletronicoController($eletronicoModel);
    $controller->atualizar();
    exit;
}

// ==========================================
// ROTA NÃO ENCONTRADA (404)
// ==========================================
http_response_code(404);
echo json_encode(["status" => "erro", "mensagem" => "Rota não encontrada."]);
exit;