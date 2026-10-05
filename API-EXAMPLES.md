# Exemplos de Uso da API Webblioteca

Este documento fornece exemplos práticos de como usar a API REST v1 da Webblioteca.

## Setup Inicial

### 1. Gerar um Token

```bash
./vendor/bin/sail artisan activities:issue-token "meu-dashboard" --email=admin@admin.com
```

**Saída:**
```
Token gerado — copie agora, ele não será mostrado novamente:
1|QSSjXY1PK3kAnCsZSgcqgNqLmIOu7sznr52bmQB6422a8532
```

Salve este token em um local seguro. Você precisará dele para fazer requisições autenticadas.

## Verificar Status da API

### Health Check (sem autenticação)

```bash
curl -X GET http://localhost:8678/api/v1/health
```

**Resposta esperada (200 OK):**
```json
{
  "status": "ok",
  "database": "ok",
  "timestamp": "2026-10-01T16:30:00-03:00"
}
```

## Listar Atividades

### Primeira Requisição (sem filtros)

```bash
curl -X GET http://localhost:8678/api/v1/activities \
  -H "Authorization: Bearer 1|QSSjXY1PK3kAnCsZSgcqgNqLmIOu7sznr52bmQB6422a8532"
```

**Resposta esperada (200 OK):**
```json
{
  "data": [
    {
      "id": 1,
      "type": "livro.criado",
      "entity_type": "App\\Models\\Livro",
      "entity_id": 1,
      "summary": {
        "titulo": "Dom Casmurro",
        "autor": "Machado de Assis"
      },
      "actor": {
        "id": 1,
        "name": "Admin"
      },
      "created_at": "2026-10-01T12:00:00-03:00"
    }
  ],
  "meta": {
    "count": 1,
    "next_since_id": 1
  }
}
```

### Segunda Requisição (usando cursor para polling)

Pegue o valor de `next_since_id` da resposta anterior e use como `since_id`:

```bash
curl -X GET "http://localhost:8678/api/v1/activities?since_id=1&limit=10" \
  -H "Authorization: Bearer 1|QSSjXY1PK3kAnCsZSgcqgNqLmIOu7sznr52bmQB6422a8532"
```

Esta requisição retornará apenas atividades com `id > 1`, permitindo polling incremental sem duplicação.

### Filtragem com Limite Customizado

```bash
curl -X GET "http://localhost:8678/api/v1/activities?limit=50" \
  -H "Authorization: Bearer 1|QSSjXY1PK3kAnCsZSgcqgNqLmIOu7sznr52bmQB6422a8532"
```

> **Nota:** O máximo permitido é 100 registros por requisição. Valores acima disso retornarão erro 422.

## Tratamento de Erros

### Sem Autenticação

```bash
curl -X GET http://localhost:8678/api/v1/activities
```

**Resposta esperada (401 Unauthorized):**
```json
{
  "error": {
    "message": "Unauthenticated.",
    "code": "AuthenticationException"
  }
}
```

### Token sem Ability Correta

```bash
# Token criado sem a ability 'activities:read'
curl -X GET http://localhost:8678/api/v1/activities \
  -H "Authorization: Bearer TOKEN_SEM_ABILITY"
```

**Resposta esperada (403 Forbidden):**
```json
{
  "error": {
    "message": "This action is unauthorized.",
    "code": "AuthorizationException"
  }
}
```

### Parâmetros Inválidos

```bash
curl -X GET "http://localhost:8678/api/v1/activities?limit=500" \
  -H "Authorization: Bearer 1|QSSjXY1PK3kAnCsZSgcqgNqLmIOu7sznr52bmQB6422a8532"
```

**Resposta esperada (422 Unprocessable Entity):**
```json
{
  "error": {
    "message": "The limit field must not be greater than 100. (and 1 more error)",
    "code": "ValidationException"
  }
}
```

## Padrão de Polling Recomendado

```javascript
// JavaScript/Node.js

const TOKEN = "seu_token_aqui";
const API_BASE = "http://localhost:8678/api/v1";

let lastId = 0;
const pollInterval = 5000; // 5 segundos

async function pollActivities() {
  try {
    const query = lastId ? `?since_id=${lastId}&limit=50` : "?limit=50";
    
    const response = await fetch(`${API_BASE}/activities${query}`, {
      headers: {
        "Authorization": `Bearer ${TOKEN}`
      }
    });

    if (!response.ok) {
      const error = await response.json();
      console.error(`Erro ${response.status}:`, error.error.message);
      return;
    }

    const data = await response.json();
    
    // Processar atividades
    data.data.forEach(activity => {
      console.log(`[${activity.type}] ${activity.summary}`);
    });

    // Atualizar cursor para próxima requisição
    if (data.data.length > 0) {
      lastId = data.meta.next_since_id;
      console.log(`Próxima sincronização a partir de: ${lastId}`);
    }

  } catch (error) {
    console.error("Erro ao sincronizar:", error);
  }
}

// Sincronizar a cada 5 segundos
setInterval(pollActivities, pollInterval);

// Sincronizar imediatamente ao iniciar
pollActivities();
```

## Tipos de Evento e Seus Resumos

### `livro.criado`
```json
{
  "type": "livro.criado",
  "summary": {
    "titulo": "Nome do Livro",
    "autor": "Nome do Autor"
  }
}
```

### `emprestimo.criado`
```json
{
  "type": "emprestimo.criado",
  "summary": {
    "livro": "Dom Casmurro",
    "exemplar": "LIV-001-1",
    "data_prevista_devolucao": "2026-10-08"
  }
}
```

### `emprestimo.devolvido`
```json
{
  "type": "emprestimo.devolvido",
  "summary": {
    "livro": "Dom Casmurro",
    "exemplar": "LIV-001-1"
  }
}
```

### `reserva.criada`
```json
{
  "type": "reserva.criada",
  "summary": {
    "sala": "Sala 101",
    "data": "2026-10-02",
    "hora_inicio": "14:00",
    "hora_fim": "15:00"
  }
}
```

## Rate Limiting

A API permite 60 requisições por minuto por token:

```bash
curl -i http://localhost:8678/api/v1/activities \
  -H "Authorization: Bearer seu_token"
```

**Verifique os headers na resposta:**
```
X-RateLimit-Limit: 60
X-RateLimit-Remaining: 59
X-RateLimit-Reset: 1696277400
```

Se exceder o limite:

**Resposta (429 Too Many Requests):**
```json
{
  "error": {
    "message": "Rate limit exceeded",
    "code": "ThrottleRequests"
  }
}
```

## Integração com Dashboard Externo

Um serviço externo pode consumir esta API para exibir atividades em tempo real:

```bash
#!/bin/bash

# dashboard-poller.sh
TOKEN="seu_token_aqui"
ENDPOINT="http://localhost:8678/api/v1/activities"
LAST_ID=0

while true; do
  RESPONSE=$(curl -s "$ENDPOINT?since_id=$LAST_ID" \
    -H "Authorization: Bearer $TOKEN")
  
  COUNT=$(echo "$RESPONSE" | jq '.meta.count')
  
  if [ "$COUNT" -gt 0 ]; then
    echo "$(date): $COUNT novas atividades encontradas"
    echo "$RESPONSE" | jq '.data[] | "\(.created_at): [\(.type)] \(.summary)"'
    
    LAST_ID=$(echo "$RESPONSE" | jq '.meta.next_since_id')
  fi
  
  sleep 5
done
```

---

Para mais informações, consulte a [documentação técnica](./DOCUMENTACAO-TECNICA.md) ou o [README](./README.md#-api-rest-v1) do projeto.
