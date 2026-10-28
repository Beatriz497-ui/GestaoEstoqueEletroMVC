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

    /**
     * ROTA 01
     */
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


    /**
     * ROTA 02
     */
    public function cadastrar(): void
    {
        // Lê o corpo da requisição JSON enviado pelo Postman
        $rawInput = file_get_contents('php://input');
        $dados = json_decode($rawInput, true);

        // Validação simples dos campos obrigatórios
        if (
            empty($dados['nome']) || 
            empty($dados['numero_lote']) || 
            empty($dados['fabricante']) || 
            empty($dados['categoria']) || 
            !isset($dados['preco'])
        ) {
            JsonView::render(400, [
                "status" => "erro",
                "mensagem" => "Campos obrigatórios ausentes: nome, numero_lote, fabricante, categoria e preco são necessários."
            ]);
            return;
        }

        try {
            $idCriado = $this->model->cadastrar($dados);

            JsonView::render(201, [
                "status" => "sucesso",
                "mensagem" => "Eletrónico cadastrado com sucesso e estoque inicializado.",
                "dados" => array_merge(["id_eletronico" => $idCriado], $dados)
            ]);
        } catch (\PDOException $e) {
            JsonView::render(500, [
                "status" => "erro",
                "mensagem" => "Erro ao cadastrar produto no banco de dados: " . $e->getMessage()
            ]);
        }
    }

    /**
     * Rota 03
     */
    public function atualizar(): void
    {
        $rawInput = file_get_contents('php://input');
        $dados = json_decode($rawInput, true);

        // Validação do ID obrigatório
        if (empty($dados['id'])) {
            JsonView::render(400, [
                "status" => "erro",
                "mensagem" => "O campo 'id' é obrigatório para atualização."
            ]);
            return;
        }

        $id = (int) $dados['id'];

        // Verifica se o produto existe
        $produtoExistente = $this->model->buscarPorId($id);
        if (!$produtoExistente) {
            JsonView::render(404, [
                "status" => "erro",
                "mensagem" => "Eletrônico com ID {$id} não encontrado."
            ]);
            return;
        }

        try {
            $sucesso = $this->model->atualizar($id, $dados);

            if ($sucesso) {
                // Busca os dados atualizados para retornar no JSON
                $produtoAtualizado = $this->model->buscarPorId($id);

                JsonView::render(200, [
                    "status" => "sucesso",
                    "mensagem" => "Eletrônico atualizado com sucesso.",
                    "dados" => $produtoAtualizado
                ]);
            } else {
                JsonView::render(400, [
                    "status" => "erro",
                    "mensagem" => "Nenhum campo válido foi fornecido para atualização."
                ]);
            }
        } catch (\PDOException $e) {
            JsonView::render(500, [
                "status" => "erro",
                "mensagem" => "Erro ao atualizar produto no banco de dados: " . $e->getMessage()
            ]);
        }
    }
}