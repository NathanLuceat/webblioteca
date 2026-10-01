<?php

namespace Tests\Feature\Api\V1;

use App\Models\Livro;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ActivityApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_rejeita_acesso_sem_autenticacao(): void
    {
        $response = $this->getJson('/api/v1/activities');

        $response->assertStatus(401);
        $response->assertJsonStructure(['error' => ['message', 'code']]);
    }

    public function test_rejeita_token_sem_a_ability_correta(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['outra-coisa']);

        $response = $this->getJson('/api/v1/activities');

        $response->assertStatus(403);
    }

    public function test_lista_atividades_com_token_valido(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['activities:read']);

        Livro::create(['titulo' => 'Livro de Teste', 'autor' => 'Autor Teste']);

        $response = $this->getJson('/api/v1/activities');

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonPath('data.0.type', 'livro.criado');
    }

    public function test_filtro_since_id_retorna_apenas_atividades_mais_recentes(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['activities:read']);

        $livro1 = Livro::create(['titulo' => 'Livro 1', 'autor' => 'Autor']);
        $livro2 = Livro::create(['titulo' => 'Livro 2', 'autor' => 'Autor']);
        $livro3 = Livro::create(['titulo' => 'Livro 3', 'autor' => 'Autor']);

        $primeiraAtividade = \App\Models\Activity::first();

        $response = $this->getJson("/api/v1/activities?since_id={$primeiraAtividade->id}");

        $response->assertStatus(200);
        $response->assertJsonCount(2, 'data');
    }

    public function test_limite_acima_de_100_e_rejeitado(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['activities:read']);

        $response = $this->getJson('/api/v1/activities?limit=500');

        $response->assertStatus(422);
    }

    public function test_observer_registra_criacao_de_livro_com_o_ator_correto(): void
    {
        $user = User::factory()->create(['name' => 'Fulano de Tal']);
        $this->actingAs($user);

        Livro::create(['titulo' => 'Dom Casmurro', 'autor' => 'Machado de Assis']);

        $this->assertDatabaseHas('activities', [
            'type' => 'livro.criado',
            'actor_id' => $user->id,
        ]);
    }

    public function test_endpoint_health_responde_sem_autenticacao(): void
    {
        $response = $this->getJson('/api/v1/health');

        $response->assertStatus(200);
        $response->assertJsonPath('status', 'ok');
    }
}