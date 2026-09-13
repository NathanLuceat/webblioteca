<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Livro;
use App\Models\Exemplar;

class LivroSeeder extends Seeder
{
    public function run(): void
    {
        $livros = [
            ['titulo' => 'Dom Casmurro', 'autor' => 'Machado de Assis', 'categoria' => 'Literatura Brasileira', 'ano_publicacao' => 1899, 'copias' => 2],
            ['titulo' => 'O Cortiço', 'autor' => 'Aluísio Azevedo', 'categoria' => 'Literatura Brasileira', 'ano_publicacao' => 1890, 'copias' => 1],
            ['titulo' => '1984', 'autor' => 'George Orwell', 'categoria' => 'Ficção Científica', 'ano_publicacao' => 1949, 'copias' => 3],
            ['titulo' => 'O Senhor dos Anéis', 'autor' => 'J.R.R. Tolkien', 'categoria' => 'Fantasia', 'ano_publicacao' => 1954, 'copias' => 2],
            ['titulo' => 'Sapiens: Uma Breve História da Humanidade', 'autor' => 'Yuval Noah Harari', 'categoria' => 'Não-ficção', 'ano_publicacao' => 2011, 'copias' => 2],
            ['titulo' => 'A Revolução dos Bichos', 'autor' => 'George Orwell', 'categoria' => 'Ficção', 'ano_publicacao' => 1945, 'copias' => 2],
            ['titulo' => 'Capitães da Areia', 'autor' => 'Jorge Amado', 'categoria' => 'Literatura Brasileira', 'ano_publicacao' => 1937, 'copias' => 1],
            ['titulo' => 'Clean Code', 'autor' => 'Robert C. Martin', 'categoria' => 'Tecnologia', 'ano_publicacao' => 2008, 'copias' => 2],
            ['titulo' => 'Harry Potter e a Pedra Filosofal', 'autor' => 'J.K. Rowling', 'categoria' => 'Fantasia', 'ano_publicacao' => 1997, 'copias' => 3],
            ['titulo' => 'Vidas Secas', 'autor' => 'Graciliano Ramos', 'categoria' => 'Literatura Brasileira', 'ano_publicacao' => 1938, 'copias' => 1],
        ];

        foreach ($livros as $dados) {
            $livro = Livro::create([
                'titulo' => $dados['titulo'],
                'autor' => $dados['autor'],
                'categoria' => $dados['categoria'],
                'ano_publicacao' => $dados['ano_publicacao'],
            ]);

            for ($i = 1; $i <= $dados['copias']; $i++) {
                Exemplar::create([
                    'livro_id' => $livro->id,
                    'codigo_patrimonio' => 'LIV-' . str_pad($livro->id, 3, '0', STR_PAD_LEFT) . '-' . $i,
                    'status' => 'disponivel',
                ]);
            }
        }
    }
}