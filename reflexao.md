## [Data] — [HTTP] [rota]
Integrantes e papéis: Driver: ... | Navigator: ...
Problema: ...
Nossa tentativa: ...
Prompt enviado à IA: “...”
Sugestão da IA: ...
Decisão da dupla: aceitar / adaptar / rejeitar — porque ...
Teste realizado: ...
Resultado obtido: HTTP ... | JSON ...
Evidência: captura/arquivo/commit ...
O que aprendemos: ...

--------------



### Registro de Interação - Fase 3 (Rota 1: GET /eletronico)
Dupla:Beatriz Torres Mozaner dos Santos  | Hannah da Silva Wegermann (Driver/Navigator)  

**Problema:** Mapeamento, implementação e validação da primeira rota pública `GET /eletronico` (Listar eletrônicos ativos e saldos) utilizando a arquitetura MVC com PDO.

**Prompt:** "Faça um apanhado do Markdown para colocar na primeira rota"

**Sugestão:** Formatação e correção de regras no arquivo `.htaccess` para resolução de erro HTTP 403/404, associação da consulta SQL com `LEFT JOIN` / `COALESCE` para retorno seguro do saldo zerado e teste da rota no Postman[cite: 1, 2].

- **Decisão da dupla:** A dupla configurou o arquivo `api_eletronicos/.htaccess` com as quebras de linha adequadas, integrou o Model `Eletronico::listarTodos()` ao Controller `EletronicoController::listar()` e realizou o teste no Postman.

- **Alteração:** 
  - Ajuste na formatação do arquivo `api_eletronicos/.htaccess` para reescrita correta das rotas virtuais no Front Controller (`public/index.php`).

  **----------Implementação do método `listarTodos()` em `app/Models/Eletronico.php` com o filtro `WHERE e.ativo = 1` e `COALESCE(est.quantidade_atual, 0)`[cite: 1, 2].
  - Implementação do método `listar()` em `app/Controllers/EletronicoController.php` utilizando `JsonView::sucesso()`[cite: 1, 2].

- **Evidência de teste:** Caso CT01 (Plano de Testes) executado no Postman com sucesso na URL `http://localhost/GestaoEstoqueEletroMVC/api_eletronicos/eletronico`:
  - **Código HTTP:** `200 OK`
  - **JSON de Resposta:** `{"erro": false, "dados": [...]}` contendo a lista dos 3 eletrônicos cadastrados e seus respetivos saldos de estoque (8, 4 e 25)[cite: 1, 2].