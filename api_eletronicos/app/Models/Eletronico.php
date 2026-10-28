<?php

namespace App\Models;

use PDO;

class Eletronico
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }


    /**
     * ROTA 01
     */
    public function listarAtivos(): array
    {
        $sql = "SELECT 
                    e.id_eletronico,
                    e.nome,
                    e.descricao,
                    e.numero_lote,
                    e.fabricante,
                    e.categoria,
                    e.preco,
                    e.estoque_minimo,
                    COALESCE(s.quantidade_atual, 0) AS saldo
                FROM eletronicos e
                LEFT JOIN estoque s ON e.id_eletronico = s.id_eletronico
                WHERE e.ativo = 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * ROTA 02
     */
    public function cadastrar(array $dados): int
    {
        // 1. Insere o produto na tabela eletronicos
        $sql = "INSERT INTO eletronicos (nome, descricao, numero_lote, fabricante, categoria, preco, estoque_minimo, ativo) 
                VALUES (:nome, :descricao, :numero_lote, :fabricante, :categoria, :preco, :estoque_minimo, 1)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':nome'           => $dados['nome'],
            ':descricao'      => $dados['descricao'] ?? null,
            ':numero_lote'    => $dados['numero_lote'],
            ':fabricante'     => $dados['fabricante'],
            ':categoria'      => $dados['categoria'],
            ':preco'          => $dados['preco'],
            ':estoque_minimo' => $dados['estoque_minimo'] ?? 0
        ]);

        $idEletronico = (int) $this->db->lastInsertId();

        // 2. Inicializa o estoque na tabela estoque com quantidade 0
        $sqlEstoque = "INSERT INTO estoque (id_eletronico, quantidade_atual) VALUES (:id_eletronico, 0)";
        $stmtEstoque = $this->db->prepare($sqlEstoque);
        $stmtEstoque->execute([':id_eletronico' => $idEletronico]);

        return $idEletronico;
    }

    /**
     * Busca um eletrônico pelo ID
     */
    public function buscarPorId(int $id): ?array
    {
        $sql = "SELECT * FROM eletronicos WHERE id_eletronico = :id AND ativo = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $resultado = $stmt->fetch();

        return $resultado ?: null;
    }

    /**
     * ROTA 03
     */
    public function atualizar(int $id, array $dados): bool
    {
        // Constrói a query dinamicamente apenas com os campos enviados
        $campos = [];
        $parametros = [':id' => $id];

        $mapaCampos = [
            'nome' => 'nome',
            'descricao' => 'descricao',
            'numero_lote' => 'numero_lote',
            'fabricante' => 'fabricante',
            'categoria' => 'categoria',
            'preco' => 'preco',
            'estoque_minimo' => 'estoque_minimo'
        ];

        foreach ($mapaCampos as $jsonChave => $colunaBanco) {
            if (array_key_exists($jsonChave, $dados)) {
                $campos[] = "{$colunaBanco} = :{$jsonChave}";
                $parametros[":{$jsonChave}"] = $dados[$jsonChave];
            }
        }

        if (empty($campos)) {
            return false;
        }

        $sql = "UPDATE eletronicos SET " . implode(', ', $campos) . " WHERE id_eletronico = :id";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute($parametros);
    }
}