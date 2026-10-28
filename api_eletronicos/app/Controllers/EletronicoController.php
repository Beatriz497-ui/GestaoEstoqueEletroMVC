<?php

namespace App\Controllers;

use App\Models\Eletronico;
use App\Views\JsonView;

class EletronicoController
{
    private Eletronico $model;

    public function __construct(Eletronico $model)
    {
        $this->model = $model;
    }

    public function listar(): void
    {
        try {
            $produtos = $this->model->listarAtivos();

            JsonView::render(200, [
                "status" => "sucesso",
                "dados" => $produtos
            ]);
        } catch (\PDOException $e) {
            JsonView::render(500, [
                "status" => "erro",
                "mensagem" => "Erro ao consultar o banco de dados."
            ]);
        }
    }
}