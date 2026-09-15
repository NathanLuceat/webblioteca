# 📘 Documentação Técnica — Webblioteca

> Documentação da lógica e estrutura implementadas no projeto. O objetivo de quem for trabalhar a partir daqui é **exclusivamente visual/estético** — layout, CSS, componentes, responsividade. Nenhuma regra de negócio, rota, controller ou nome de campo deve ser alterado sem necessidade explícita.

## Visão geral

**Webblioteca** é um sistema de biblioteca com duas funcionalidades centrais: empréstimo de livros e reserva de salas de estudo. Feito como projeto de portfólio, com foco em demonstrar modelagem de banco de dados relacional e lógica de backend com Laravel.

## Stack técnica

- **Backend**: Laravel (PHP 8.5), rodando via Laravel Sail
- **Banco de dados**: MySQL 8.4
- **Ambiente**: Docker (orquestrado pelo Sail — `compose.yaml` já configurado)
- **Frontend**: Blade (template engine nativo do Laravel), sem framework JS adicional
- **Autenticação**: Laravel Breeze (stack Blade)
- **Localização do projeto**: `/home/[usuario]/webblioteca` (dentro do WSL/Ubuntu)

### Como o ambiente é executado

```bash
cd ~/webblioteca
./vendor/bin/sail up -d
```

Acesso via `http://localhost`.

## Schema do banco de dados (6 tabelas)

| Tabela | Colunas principais | Relacionamentos |
|---|---|---|
| `users` | id, name, email, password, email_verified_at, timestamps (padrão Breeze) | 1-N com emprestimos e reservas_salas |
| `livros` | id, titulo, autor, categoria, ano_publicacao, timestamps | 1-N com exemplares |
| `exemplares` | id, livro_id (FK), codigo_patrimonio, status ('disponivel'/'emprestado'), timestamps | N-1 com livros, 1-N com emprestimos |
| `salas` | id, nome, capacidade, localizacao, timestamps | 1-N com reservas_salas |
| `emprestimos` | id, usuario_id (FK), exemplar_id (FK), data_emprestimo, data_prevista_devolucao, data_devolucao (nullable), timestamps | N-1 com users e exemplares |
| `reservas_salas` | id, usuario_id (FK), sala_id (FK), data, hora_inicio, hora_fim, timestamps | N-1 com users e salas |

## Models (Eloquent) — `app/Models/`

- **`Livro`** — `hasMany(Exemplar)`
- **`Exemplar`** — `belongsTo(Livro)`, `hasMany(Emprestimo)`. `$table` definido explicitamente como `'exemplares'` (pluralização automática do Laravel erraria esse nome).
- **`Sala`** — `hasMany(ReservaSala)`
- **`Emprestimo`** — `belongsTo(User, 'usuario_id')`, `belongsTo(Exemplar)`
- **`ReservaSala`** — `belongsTo(User, 'usuario_id')`, `belongsTo(Sala)`. `$table` definido explicitamente como `'reservas_salas'`. Contém dois métodos estáticos auxiliares: `blocosDisponiveis()` (retorna os 12 blocos fixos de horário, das 06:00 às 18:00) e `diasPermitidos()` (retorna os 3 dias fixos: hoje, amanhã, depois de amanhã).

## Rotas — `routes/web.php`

```
GET  /                      → view welcome (padrão Laravel, não usada na prática)
GET  /dashboard              → dashboard do usuário logado (protegida: auth, verified)
GET  /livros                 → LivroController@index (lista de livros)
GET  /livros/{id}            → LivroController@show (detalhe + exemplares)
GET  /salas                  → SalaController@index (lista de salas)
GET  /salas/{id}             → SalaController@show (detalhe + formulário de reserva)

--- protegidas por middleware('auth') ---
GET  /meus-emprestimos           → EmprestimoController@index
POST /emprestimos                → EmprestimoController@store
POST /emprestimos/{id}/devolver  → EmprestimoController@devolver
GET  /minhas-reservas            → ReservaController@index
POST /reservas                   → ReservaController@store
POST /reservas/{id}/cancelar     → ReservaController@cancelar

--- rotas do Breeze (auth.php) ---
/login, /register, /logout, /profile, etc. (não customizadas)
```

## Controllers — `app/Http/Controllers/`

### `LivroController`

- `index()` — lista todos os livros.
- `show($id)` — mostra um livro e seus exemplares (via relacionamento `$livro->exemplares`).

### `EmprestimoController`

- `index()` — lista empréstimos ativos (`data_devolucao IS NULL`) do usuário logado, com eager loading de `exemplar.livro`.
- `store()` — valida `exemplar_id`, verifica se o exemplar está `disponivel`, cria o empréstimo (prazo de 7 dias a partir de hoje) e marca o exemplar como `emprestado`.
- `devolver(Emprestimo $emprestimo)` — usa Route Model Binding. Verifica que o empréstimo pertence ao usuário logado (`abort(403)` caso contrário), preenche `data_devolucao` e devolve o exemplar para `disponivel`.

### `SalaController`

- `index()` — lista todas as salas.
- `show($id)` — monta a view de detalhe com: a sala, os 3 dias permitidos (`ReservaSala::diasPermitidos()`), os 12 blocos de horário, e um array `$ocupados` (horários já reservados por dia, calculado consultando `reservas_salas` filtrado pela sala e pelos 3 dias permitidos).

### `ReservaController`

- `index()` — **antes de listar**, apaga (lazy cleanup) qualquer reserva do usuário cujo `data + hora_fim` já passou (comparação via Carbon `isPast()`). Só então lista as reservas restantes, ordenadas por data/hora.
- `store()` — recebe `sala_id`, `dias[]` (array, um ou mais dos 3 dias permitidos) e `blocos[]` (array, até 3 horários). Valida com `Rule::in(...)` que tudo está dentro do permitido. Faz duas passadas: primeiro valida (sem criar nada) se há conflito de sala ocupada ou do próprio usuário já reservado no mesmo horário/dia, e se o limite de 3 blocos por dia/sala/usuário não é excedido; só depois de tudo validado, cria de fato uma linha por combinação dia×bloco.
- `cancelar(ReservaSala $reserva)` — mesma lógica de posse (403) do `devolver`, depois `delete()`.

## Regras de negócio importantes (não alterar)

1. **Empréstimo**: prazo fixo de 7 dias; só é possível emprestar exemplar com status `disponivel`; devolução só pelo próprio usuário.
2. **Reserva de sala**: só 3 dias possíveis (hoje/amanhã/depois de amanhã); 12 blocos fixos de 1h (06:00–18:00); máximo 3 blocos por dia, por sala, por usuário (contando reservas já existentes + as novas na mesma submissão); um usuário não pode ter duas reservas (em salas diferentes) no mesmo dia/horário; uma sala não pode ter duas reservas no mesmo dia/horário (checado para qualquer usuário); reservas expiradas são removidas automaticamente quando o próprio usuário acessa `/minhas-reservas`.
3. Toda ação de escrita (`store`, `devolver`, `cancelar`) exige autenticação (`middleware('auth')`).

## Views (Blade) — `resources/views/`

| Arquivo | Status visual atual | Observação |
|---|---|---|
| `dashboard.blade.php` | Usa `<x-app-layout>` (componente do Breeze) | Já integrado ao layout padrão, com Tailwind |
| `livros/index.blade.php`, `livros/show.blade.php` | HTML puro, sem layout | **Candidatos a migrar para `<x-app-layout>`** |
| `salas/index.blade.php`, `salas/show.blade.php` | HTML puro, sem layout | Idem |
| `emprestimos/index.blade.php` | HTML puro, sem layout | Idem |
| `reservas/index.blade.php` | HTML puro, sem layout | Idem |
| `welcome.blade.php` | Padrão do Laravel, não customizada | Pode ser substituída por uma landing page própria |
| `auth/*`, `layouts/*`, `profile/*` | Geradas pelo Breeze | Já estilizadas com Tailwind |

### O que é seguro alterar nessas views

- Toda a estrutura HTML/CSS, classes, cores, layout, responsividade.
- Envolver o conteúdo das views "puras" (`livros`, `salas`, `emprestimos`, `reservas`) no componente `<x-app-layout>` do Breeze, para ficarem visualmente consistentes com o dashboard.
- Adicionar links de navegação (ex: no menu do Breeze, em `resources/views/layouts/navigation.blade.php`) apontando para `/livros`, `/salas`, `/meus-emprestimos`, `/minhas-reservas`.

### O que NÃO deve ser alterado nessas views (quebra a lógica)

- Os atributos `action` e `method` de cada `<form>`.
- A presença e posição da diretiva `@csrf` dentro de cada formulário.
- Os atributos `name` dos inputs: `exemplar_id`, `sala_id`, `dias[]`, `blocos[]` — esses nomes são lidos diretamente pelos Controllers via `$request->`.
- As diretivas Blade que controlam dados dinâmicos: `@foreach`, `@forelse`, `@auth`/`@endauth`, `@if (session(...))`, `@if ($errors->any())` — o **conteúdo visual** dentro delas pode mudar livremente, mas a estrutura de controle e as variáveis referenciadas (`$livros`, `$livro->exemplares`, `$reservas`, `$dias`, `$blocos`, `$ocupados`, `session('sucesso')`, `session('erro')`, `$errors`) devem continuar sendo usadas.
- Os valores enviados nos `<input type="hidden">` (ex: `exemplar_id`, `sala_id`).

## Dados de teste (Seeders)

`database/seeders/LivroSeeder.php` e `SalaSeeder.php` populam 10 livros (com exemplares variados) e 5 salas. Rodados via:

```bash
./vendor/bin/sail artisan migrate:fresh --seed
```

## Autenticação

Implementada via Laravel Breeze (stack Blade + Alpine.js). Rotas de `/login`, `/register`, `/logout`, `/profile` já funcionais e estilizadas — não fazem parte do escopo de views customizadas listado acima, mas podem receber ajustes visuais de identidade (cores, logo) se desejado, desde que a estrutura de formulário do Breeze não seja quebrada.

## Metodologia linear para adicionar conteúdos pelo painel administrativo

Esta sequência deve ser seguida na ordem indicada, pois cada etapa prepara a estrutura necessária para a próxima. O objetivo é permitir que um administrador cadastre livros e salas para demonstração, identificando esses registros como temporários e removendo-os automaticamente após 24 horas.

### 1. Criar as migrations de estrutura

Criar três migrations pequenas, sem alterar as tabelas existentes diretamente:

- Adicionar `is_admin` à tabela `users`, com valor padrão `false`.
- Adicionar `temporario` à tabela `livros`, com valor padrão `false`.
- Adicionar `temporario` à tabela `salas`, com valor padrão `false`.

O campo `is_admin` identifica usuários autorizados a acessar o painel. Os campos `temporario` diferenciam os registros permanentes criados pelos seeders dos livros e salas cadastrados pelo administrador para demonstração. Os registros temporários devem ser removidos quando tiverem mais de 24 horas desde `created_at`.

### 2. Atualizar os models

Adicionar `temporario` ao `$fillable` dos models `Livro` e `Sala`. Adicionar `is_admin` ao `$fillable` do model `User`, caso o Seeder utilize atribuição em massa para esse campo.

Também é recomendado declarar casts booleanos para `is_admin` e `temporario`, para que as verificações sejam tratadas como valores booleanos pelo Eloquent.

### 3. Criar o Seeder do administrador

Criar `AdminSeeder` para gerar uma conta administrativa fixa, usando uma senha criptografada com `bcrypt()` ou `Hash::make()`. A conta de demonstração deve ter suas credenciais documentadas no `README.md`.

O Seeder deve ser idempotente ou tratar a possibilidade de a conta já existir, evitando duplicar o administrador quando for executado mais de uma vez. Registrar `AdminSeeder` no `DatabaseSeeder`, junto aos seeders de livros e salas.

### 4. Criar os middlewares

Criar dois middlewares independentes:

- `EhAdmin`: verifica se há um usuário autenticado e se `is_admin` é verdadeiro; caso contrário, interrompe a requisição com resposta `403`.
- `LimpezaAutomatica`: executa em toda requisição web e remove reservas expiradas de todos os usuários, além de livros e salas marcados como `temporario` cujo `created_at` seja anterior a 24 horas.

A limpeza de reservas deve continuar respeitando o timezone `America/Sao_Paulo`, pois os horários dos blocos representam horário local. A limpeza global substitui a necessidade de agendamento para este ambiente de demonstração.

### 5. Registrar os middlewares

Registrar `LimpezaAutomatica` no grupo global de middleware web em `bootstrap/app.php`, para que ela rode antes das rotas web serem processadas, independentemente de o visitante estar autenticado.

Registrar `EhAdmin` como alias, por exemplo `admin`, para que as rotas administrativas possam usar `middleware(['auth', 'admin'])` sem repetir o namespace completo da classe.

### 6. Criar o AdminController

Criar o controller administrativo com ações separadas para cada fluxo:

- `painel()`: exibe a página inicial do painel.
- `livrosForm()`: exibe o formulário de cadastro de livros.
- `livrosStore()`: valida os dados, cria o livro com `temporario = true` e cria seus exemplares.
- `salasForm()`: exibe o formulário de cadastro de salas.
- `salasStore()`: valida os dados e cria a sala com `temporario = true`.
- `reservas()`: lista as reservas de todos os usuários, carregando os relacionamentos `sala` e `usuario`.

Os métodos de gravação devem validar os campos recebidos e usar os nomes de entrada definidos nas respectivas views. O cadastro de livros deve criar pelo menos um exemplar associado ao novo livro.

### 7. Adicionar as rotas administrativas

Adicionar em `routes/web.php` um grupo com prefixo `/admin`, protegido por autenticação e pelo alias `admin`:

- `GET /admin`: painel principal.
- `GET /admin/livros`: formulário de cadastro de livro.
- `POST /admin/livros`: grava o livro e seus exemplares.
- `GET /admin/salas`: formulário de cadastro de sala.
- `POST /admin/salas`: grava a sala.
- `GET /admin/reservas`: exibe as reservas de todos os usuários.

Nomear as rotas quando isso facilitar a navegação nas views. Nenhuma rota pública existente deve perder seu comportamento.

### 8. Criar as views do painel

Criar quatro views em `resources/views/admin/`:

- `painel.blade.php`: links para cadastrar livros, cadastrar salas e consultar reservas.
- `livros.blade.php`: formulário com título, autor, categoria, ano de publicação e quantidade de exemplares.
- `salas.blade.php`: formulário com nome, capacidade e localização.
- `reservas.blade.php`: listagem das reservas agrupadas ou organizadas por usuário, mostrando sala, data e horário.

As views devem preservar `@csrf`, os métodos HTTP dos formulários, os nomes dos campos esperados pelos controllers e a exibição de mensagens de sucesso e erros de validação. Sempre que possível, utilizar o layout visual já adotado pelo Breeze em vez de criar páginas HTML isoladas.

### 9. Documentar as credenciais e o fluxo

Atualizar o `README.md` com:

- credenciais da conta administrativa de demonstração;
- endereço do painel administrativo;
- instruções para iniciar o ambiente;
- aviso de que livros e salas cadastrados pelo painel são temporários e expiram após 24 horas;
- explicação de que a limpeza acontece quando uma nova requisição web é recebida.

Nunca documentar ou armazenar senhas em texto puro no banco. A credencial no README é apenas para facilitar o teste do projeto público.

### 10. Executar migrations e seeders

Aplicar somente as migrations pendentes para preservar os dados existentes:

```bash
./vendor/bin/sail artisan migrate
./vendor/bin/sail artisan db:seed --class=AdminSeeder
```

Evitar `migrate:fresh` nesta etapa, pois esse comando apaga todas as tabelas e dados atuais.

### 11. Validar o fluxo completo

Executar a validação nesta ordem:

1. Confirmar que um usuário comum recebe `403` ao acessar `/admin`.
2. Fazer login com a conta administrativa e confirmar acesso ao painel.
3. Cadastrar um livro com exemplares e verificar sua presença em `/livros`.
4. Cadastrar uma sala e verificar sua presença em `/salas`.
5. Criar uma reserva usando a sala cadastrada e confirmar que ela aparece para o usuário e no painel administrativo.
6. Simular mais de 24 horas alterando `created_at` do livro ou da sala temporária no banco.
7. Recarregar qualquer página web e confirmar que o registro temporário foi removido.
8. Confirmar que livros e salas dos seeders, marcados com `temporario = false`, permanecem disponíveis.
9. Confirmar que reservas expiradas são removidas globalmente, independentemente do usuário que as criou.

Ao concluir, verificar as rotas com `./vendor/bin/sail artisan route:list`, limpar caches se necessário com `./vendor/bin/sail artisan optimize:clear` e executar os testes automatizados disponíveis.

---

## Painel administrativo — implementação (2026-09-15)

Resumo das alterações feitas para implementar o painel administrativo seguindo a metodologia linear descrita acima.

### Arquivos modificados

| Arquivo | Alteração |
|---|---|
| `config/app.php` | Timezone definido para `America/Sao_Paulo` |
| `app/Models/User.php` | `is_admin` adicionado ao `#[Fillable]` e ao `casts()` como boolean |
| `app/Models/Livro.php` | `temporario` adicionado ao `$fillable` e ao `casts()` como boolean |
| `app/Models/Sala.php` | `temporario` adicionado ao `$fillable` e ao `casts()` como boolean |
| `database/seeders/DatabaseSeeder.php` | `AdminSeeder::class` adicionado ao array de chamada |
| `bootstrap/app.php` | Imports de `EhAdmin` e `LimpezaAutomatica` adicionados; `LimpezaAutomatica` registrada no middleware global com `append()`; alias `admin` → `EhAdmin` registrada |
| `routes/web.php` | Import de `AdminController` adicionado; grupo de rotas `/admin` com middleware `['auth', 'admin']` e prefixo `admin` adicionado (6 rotas: painel, livros form/store, salas form/store, reservas) |
| `resources/views/layouts/navigation.blade.php` | Link "Painel" adicionado na nav desktop e mobile, visível apenas para `is_admin` |
| `README.md` | Seção "Painel administrativo" adicionada com credenciais, endereço, instruções e aviso sobre temporários |

### Arquivos criados

| Arquivo | Descrição |
|---|---|
| `database/migrations/2026_09_15_112811_add_is_admin_to_users_table.php` | Adiciona coluna `is_admin` (boolean, default false) à tabela `users` |
| `database/migrations/2026_09_15_112812_add_temporario_to_livros_table.php` | Adiciona coluna `temporario` (boolean, default false) à tabela `livros` |
| `database/migrations/2026_09_15_112814_add_temporario_to_salas_table.php` | Adiciona coluna `temporario` (boolean, default false) à tabela `salas` |
| `database/seeders/AdminSeeder.php` | Cria admin idempotente (`admin@admin.com` / `admin@webblioteca`, `is_admin = true`) via `updateOrCreate` |
| `app/Http/Middleware/EhAdmin.php` | Verifica `auth()->check()` e `is_admin`; abort 403 se não for admin |
| `app/Http/Middleware/LimpezaAutomatica.php` | Remove reservas expiradas (todos os usuários), livros e salas temporários com >24h de `created_at` — roda a cada requisição web |
| `app/Http/Controllers/AdminController.php` | 6 métodos: `painel`, `livrosForm`, `livrosStore` (cria livro + exemplares), `salasForm`, `salasStore`, `reservas` (lista com eager loading de sala/usuario) |
| `resources/views/admin/painel.blade.php` | Página inicial do painel com cards de navegação (livros, salas, reservas) |
| `resources/views/admin/livros.blade.php` | Formulário de cadastro: título, autor, categoria, ano, qtd de exemplares |
| `resources/views/admin/salas.blade.php` | Formulário de cadastro: nome, capacidade, localização |
| `resources/views/admin/reservas.blade.php` | Listagem de todas as reservas por usuário com sala, data e horário |
| `tests/Feature/AdminPanelFeatureTest.php` | 6 testes: 403 para não-admin, acesso admin, CRUD de livro com exemplares, CRUD de sala, limpeza de temporários >24h, limpeza global de reservas expiradas |

### Resultado dos testes

```
PHPUnit 12.5.34 — Tests: 32 passed (83 assertions)
```

### Comandos executados

```bash
./vendor/bin/sail artisan migrate          # 3 novas migrations aplicadas
./vendor/bin/sail artisan db:seed --class=AdminSeeder
./vendor/bin/sail artisan view:cache       # compilação Blade OK
```

---

## Registro de alteração — proteção da conta admin (2026-09-15)

- Senha do admin alterada de `password` para `admin@webblioteca` (atualizada no banco e no `AdminSeeder`)
- `ProfileController::destroy()` bloqueado para `is_admin = true` — retorna com erro sem excluir
- `resources/views/profile/edit.blade.php` — seções de alteração de senha e exclusão de conta removidas da view para usuários admin (`@if(!Auth::user()->is_admin)`)
- `README.md` atualizado com nova senha e aviso sobre proteção da conta