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
}