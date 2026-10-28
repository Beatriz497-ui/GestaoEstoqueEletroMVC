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



# 📝 Registro de Reflexão — Rota 1

---

### 📌 Informações Básicas
| **Data & Rota** | `28/10/2026` — `GET /eletronico` |
| **Integrantes** | **Driver:** Beatriz Torres Mozaner dos Santos **Navigator:** Hannah da Silva Wegermann
| **Evidência** | Prints no Documento

---

### 🔍 Diagnóstico & Solução

**1. Problema:**  
> Exibir a listagem de todos os eletrônicos cadastrados e ativos no sistema juntamente com os seus respetivos saldos de estoque, tratando produtos que ainda não possuem registo na tabela de estoque. Durante o processo, surgiram erros `404 Not Found` por conta da subpasta `api_eletronicos` e *Fatal Errors* por falta do carregamento manual de classes.

**2. Nossa Tentativa:**  
> Criamos o `index.php`, o `EletronicoController.php` e o model `Eletronico.php`, mas a rota falhava ao tentar aceder às classes e ao mapear a URL amigável no Apache.

**3. Prompt Enviado à IA:**  
> *"Como resolver o erro 'Call to undefined method App\Models\Eletronico::listarAtivos()' e configurar a rota GET /eletronico considerando a subpasta api_eletronicos e sem usar Composer?"*

**4. Sugestão da IA:**  
- Utilizar `LEFT JOIN` com `COALESCE(s.quantidade_atual, 0)` na consulta SQL para não ocultar produtos sem estoque.
- Mover o `.htaccess` para a pasta `public/` e ajustar a leitura do caminho da URI.
- Adicionar os `require_once` manuais para `Eletronico.php`, `EletronicoController.php` e `JsonView.php`.
- Criar o método estático `render()` na classe `JsonView`.

**5. Decisão da Dupla:**  
> **Aceitar e implementar 100% das sugestões**, pois resolveram o problema de roteamento no Apache/XAMPP e eliminaram todos os erros de carregamento de classes no PHP.

---

### 🧪 Resultado do Teste

| Métrica | Status / Conteúdo |
| :--- | :--- |
| **Requisição** | `GET http://localhost/GestaoEstoqueEletroMVC/api_eletronicos/public/eletronico` |
| **Status HTTP** | `200 OK` |
| **JSON Retornado** | Lista de eletrónicos ativos (`Notebook Vision 14`, `Smartphone Connect X`, `Cabo HDMI 2m`) com saldos `8`, `4`, `25`. |

---

### 💡 O Que Aprendemos?

1. **SQL Relacional:** A utilização do `LEFT JOIN` é indispensável quando a tabela secundária pode não conter registos correspondentes.
2. **Roteamento em PHP Puro:** Mapear e tratar URIs manualmente exige atenção especial ao diretório base quando a aplicação roda dentro de subpastas do XAMPP.
3. **Carregamento de Ficheiros:** Na ausência do Composer (autoloading PSR-4), a ordem e a presença dos `require_once` são cruciais para evitar chamadas de classes inexistentes.



=======================================================================================================

# 📝 Registro de Reflexão — Rota 2


### 🔍 Diagnóstico & Solução

**1. Problema:**  
> Cadastrar um novo produto eletrónico na base de dados, inicializar automaticamente o seu registo de estoque com quantidade zero na tabela `estoque` e tratar erros de duplicação do número de lote (`numero_lote` com restrição de unicidade). Durante a implementação, ocorreram erros de duplo JSON na resposta devido à falta de limpeza de buffer de saída (`ob_clean`) e mensagens de 404 sendo emitidas junto à resposta de sucesso.

**2. Nossa Tentativa:**  
> Implementamos o método `cadastrar()` no `Eletronico.php` e no `EletronicoController.php`, e mapeámos a rota `POST /eletronico` no `index.php`. Contudo, ao testar no Postman, o PHP continuava a execução até o final do ficheiro, juntando a mensagem `{"status":"erro","mensagem":"Rota não encontrada."}` com o JSON de resposta da API.

**3. Prompt Enviado à IA:**  
> *"Como evitar que duas respostas JSON sejam enviadas na mesma requisição POST e tratar corretamente a resposta de sucesso HTTP 201 e a falha de lote duplicado?"*

**4. Sugestão da IA:**  
- Utilizar `ob_clean()` na classe `JsonView` para limpar qualquer buffer de saída pendente antes de enviar a resposta HTTP.
- Garantir a interrupção imediata da script com `exit` ou `return` após renderizar a View.
- Estruturar a inserção em duas etapas: cadastrar o produto na tabela `eletronicos` e criar a linha inicial correspondente na tabela `estoque`.
- Tratar exceções de PDO (`PDOException`) para capturar o código de erro `1062` referente à violação da chave única de `numero_lote`.

**5. Decisão da Dupla:**  
> **Aceitar e aplicar todas as sugestões**, garantindo uma resposta JSON limpa, padronizada e com os códigos de estado HTTP corretos (`201 Created` para sucesso e `500/400` para falhas de integridade do banco).

---

### 🧪 Resultado dos Testes

| Caso | Requisição | Status HTTP | Resultado Observado |
| :--- | :--- | :--- | :--- |
| **CT03** | `POST /eletronico` (Dados novos) | `201 Created` | Produto inserido com sucesso, gerando o ID correspondente e inicializando a tabela de estoque com quantidade zero. |
| **CT04** | `POST /eletronico` (Lote existente) | `500 Internal Server Error` | Violação de unicidade capturada (`SQLSTATE[23000]: Duplicate entry`), impedindo dados duplicados no banco. |

---

### 💡 O Que Aprendemos?

1. **Gestão de Buffer no PHP:** A utilização de `ob_clean()` garante que qualquer mensagem ou aviso prévio seja descartado antes da emissão de um cabeçalho JSON limpo.
2. **Consistência de Dados (Relacionamento 1:1 Inicial):** A automação da inserção na tabela `estoque` logo após o cadastro do produto evita registos órfãos no sistema.
3. **Tratamento de Exceções de Banco de Dados:** Tratar erros de chave única (`UNIQUE constraint`) no Controller impede que a aplicação colapse e entrega um feedback estruturado ao cliente da API.



# 📝 Registro de Reflexão — Rota 03---

### 🔍 Diagnóstico & Solução

**1. Problema:**  
> Implementar a rota de atualização parcial/total dos dados de um eletrónico (`PUT /eletronico`). Durante os primeiros testes, a requisição retornava erro `404 Not Found ("Rota não encontrada")`, pois a captura do verbo HTTP no roteador central não estava tratando adequadamente o método `PUT`.

**2. Nossa Tentativa:**  
> Configurámos o método `atualizar()` no Controller e no Model para receber o payload JSON. No entanto, ao disparar o verbo `PUT` no Postman, o `$metodo` capturado no `index.php` não casava com a condição da rota.

**3. Prompt Enviado à IA:**  
> *"Por que a rota PUT /eletronico está retornando 404 Not Found mesmo com a rota declarada no index.php e como corrigir o roteamento para verbos PUT?"*

**4. Sugestão da IA:**  
- Normalizar a captura do método HTTP utilizando `strtoupper(trim($_SERVER['REQUEST_METHOD']))`.
- Garantir a leitura correta do corpo da requisição JSON via `php://input`.
- Implementar a verificação prévia da existência do registo no banco via `buscarPorId()` antes de executar a query de `UPDATE`.
- Manter o padrão de encerramento da execução com `exit` e resposta limpa através do `JsonView`.

**5. Decisão da Dupla:**  
> **Aceitar e aplicar as sugestões**, garantindo a normalização do método HTTP no `index.php` e o tratamento adequado de retornos HTTP (`200 OK` para sucesso e `404 Not Found` para IDs inexistentes).

---

### 🧪 Resultado dos Testes

| Caso | Requisição | Status HTTP | Resultado Observado |
| :--- | :--- | :--- | :--- |
| **CT05** | `PUT /eletronico` (ID existente) | `200 OK` | Produto com ID 1 atualizado com sucesso e os novos dados retornados na resposta JSON. |
| **CT06** | `PUT /eletronico` (ID inexistente) | `404 Not Found` | Tentativa de atualizar ID 9999 bloqueada com mensagem explícita de produto não encontrado. |

---

### 💡 O Que Aprendemos?

1. **Normalização de Verbos HTTP:** A conversão do método HTTP para maiúsculas previne falhas de roteamento causadas por divergências no cabeçalho enviado pelo cliente.
2. **Consultas Dinâmicas de Update:** Construir a cláusula `UPDATE` no Model apenas com os campos fornecidos no JSON permite atualizações flexíveis e evita sobrescrever dados omitidos.
3. **Validação de Existência Previa:** Verificar a presença do recurso na base de dados antes do `UPDATE` garante uma resposta semanticamente correta em REST (`404` em vez de sucesso com 0 linhas afetadas).


