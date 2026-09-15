<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPanelFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_usuario_comum_recebe_403_ao_acessar_painel()
    {
        $user = User::factory()->create(['is_admin' => false]);

        $this->actingAs($user)
            ->get('/admin')
            ->assertForbidden();

        $this->actingAs($user)
            ->get('/admin/livros')
            ->assertForbidden();
    }

    public function test_admin_acessa_painel_e_paginas()
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)->get('/admin')->assertOk();
        $this->actingAs($admin)->get('/admin/livros')->assertOk();
        $this->actingAs($admin)->get('/admin/salas')->assertOk();
        $this->actingAs($admin)->get('/admin/reservas')->assertOk();
    }

    public function test_admin_cadastra_livro_com_exemplares_temporarios()
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)->post('/admin/livros', [
            'titulo' => 'Dom Casmurro',
            'autor' => 'Machado de Assis',
            'categoria' => 'Romance',
            'ano_publicacao' => 1899,
            'qtd_exemplares' => 3,
        ])->assertRedirect(route('admin.painel'));

        $this->assertDatabaseHas('livros', [
            'titulo' => 'Dom Casmurro',
            'temporario' => true,
        ]);

        $livro = \App\Models\Livro::where('titulo', 'Dom Casmurro')->first();
        $this->assertCount(3, $livro->exemplares);
    }

    public function test_admin_cadastra_sala_temporaria()
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)->post('/admin/salas', [
            'nome' => 'Sala de Debates',
            'capacidade' => 12,
            'localizacao' => '2º andar',
        ])->assertRedirect(route('admin.painel'));

        $this->assertDatabaseHas('salas', [
            'nome' => 'Sala de Debates',
            'temporario' => true,
        ]);
    }

    public function test_limpeza_remove_livro_e_sala_temporarios_apos_24h()
    {
        $admin = User::factory()->create(['is_admin' => true]);

        // created_at não é fillable: atribuir diretamente após criar
        $livroVelho = \App\Models\Livro::create([
            'titulo' => 'Livro Velho',
            'autor' => 'A',
            'categoria' => 'B',
            'ano_publicacao' => 2000,
            'temporario' => true,
        ]);
        $livroVelho->created_at = now()->subHours(25);
        $livroVelho->save();

        $salaVelha = \App\Models\Sala::create([
            'nome' => 'Sala Velha',
            'capacidade' => 5,
            'localizacao' => 'X',
            'temporario' => true,
        ]);
        $salaVelha->created_at = now()->subHours(25);
        $salaVelha->save();

        \App\Models\Livro::create([
            'titulo' => 'Livro Recente',
            'autor' => 'A',
            'categoria' => 'B',
            'ano_publicacao' => 2000,
            'temporario' => true,
        ]);

        $this->actingAs($admin)->get('/admin');

        $this->assertDatabaseMissing('livros', ['titulo' => 'Livro Velho']);
        $this->assertDatabaseMissing('salas', ['nome' => 'Sala Velha']);
        $this->assertDatabaseHas('livros', ['titulo' => 'Livro Recente']);
    }

    public function test_limpeza_remove_reservas_expiradas_de_todos_os_usuarios()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $outro = User::factory()->create();

        $sala = \App\Models\Sala::create([
            'nome' => 'Sala Ativa',
            'capacidade' => 5,
            'localizacao' => 'X',
        ]);

        \App\Models\ReservaSala::create([
            'usuario_id' => $outro->id,
            'sala_id' => $sala->id,
            'data' => now()->subDay()->format('Y-m-d'),
            'hora_inicio' => '08:00',
            'hora_fim' => '09:00',
        ]);

        \App\Models\ReservaSala::create([
            'usuario_id' => $outro->id,
            'sala_id' => $sala->id,
            'data' => now()->addDay()->format('Y-m-d'),
            'hora_inicio' => '08:00',
            'hora_fim' => '09:00',
        ]);

        $this->actingAs($admin)->get('/admin');

        $this->assertDatabaseMissing('reservas_salas', [
            'data' => now()->subDay()->format('Y-m-d'),
        ]);
        $this->assertDatabaseHas('reservas_salas', [
            'data' => now()->addDay()->format('Y-m-d'),
        ]);
    }
}