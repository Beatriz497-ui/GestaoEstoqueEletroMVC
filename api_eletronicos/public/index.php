// Rota 1: GET /eletronico
if ($metodo === 'GET' && $uri === '/eletronico') {
    $controller = new EletronicoController($eletronicoModel);
    $controller->listar();
    exit;
}