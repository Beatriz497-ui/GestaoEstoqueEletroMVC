<?php

namespace App\Views;

class JsonView
{
    /**
     * Renderiza a resposta em formato JSON com o código de status HTTP
     *
     * @param int $statusCode Código de status HTTP (ex: 200, 201, 400, 404, 500)
     * @param mixed $dados Dados a serem convertidos para JSON
     * @return void
     */
    public static function render(int $statusCode, $dados): void
    {
        // Define o cabeçalho informando que o retorno é JSON em UTF-8
        header('Content-Type: application/json; charset=utf-8');
        
        // Define o código de resposta HTTP
        http_response_code($statusCode);

        // Converte os dados para JSON e exibe na tela
        echo json_encode($dados, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }
}