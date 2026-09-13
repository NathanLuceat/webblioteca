<?php

namespace Tests\Feature;

use App\Models\Exemplar;
use App\Models\Livro;
use Tests\TestCase;

class ModelLoadingTest extends TestCase
{
    public function test_models_are_autoloaded_and_relationship_methods_exist(): void
    {
        $this->assertTrue(class_exists(Livro::class));
        $this->assertTrue(class_exists(Exemplar::class));
        $this->assertTrue(method_exists(Livro::class, 'exemplares'));
        $this->assertTrue(method_exists(Exemplar::class, 'livro'));
    }
}
