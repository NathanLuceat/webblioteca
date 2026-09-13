<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sala;

class SalaSeeder extends Seeder
{
    public function run(): void
    {
        $salas = [
            ['nome' => 'Sala de Estudos 1', 'capacidade' => 4, 'localizacao' => '1º andar'],
            ['nome' => 'Sala de Estudos 2', 'capacidade' => 4, 'localizacao' => '1º andar'],
            ['nome' => 'Sala de Reuniões', 'capacidade' => 10, 'localizacao' => '2º andar'],
            ['nome' => 'Auditório', 'capacidade' => 40, 'localizacao' => 'Térreo'],
            ['nome' => 'Sala Silenciosa', 'capacidade' => 1, 'localizacao' => '2º andar'],
        ];

        foreach ($salas as $sala) {
            Sala::create($sala);
        }
    }
}