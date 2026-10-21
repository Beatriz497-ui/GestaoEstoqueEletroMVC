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
     * Retorna todos os produtos eletrônicos ativos com saldo de estoque.
     */
    public function listarTodos(): array
    {
        $sql = "SELECT e.*, COALESCE(est.quantidade_atual, 0) AS quantidade_atual
                FROM eletronicos e
                LEFT JOIN estoque est ON e.id_eletronico = est.id_eletronico
                WHERE e.ativo = 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}