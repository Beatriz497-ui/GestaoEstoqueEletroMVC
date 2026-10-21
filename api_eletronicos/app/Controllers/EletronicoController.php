<?php

namespace App\Controllers;

use App\Models\Eletronico;
use App\Views\JsonView;

class EletronicoController
{
    private Eletronico $eletronicoModel;

    public function __construct(Eletronico $eletronicoModel)
    {
        $this->eletronicoModel = $eletronicoModel;
    }

    /**
     * Ação responsável por listar todos os eletrônicos ativos.
     */
    public function listar(): void
    {
        $eletronicos = $this->eletronicoModel->listarTodos();
        JsonView::sucesso($eletronicos);
    }
}