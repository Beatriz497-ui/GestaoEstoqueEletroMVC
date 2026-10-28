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

