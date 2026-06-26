<?php

namespace Tests\Feature;

use App\Models\Categoria;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class MaterialControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function dadoUnMaterialQueNoExiste_insertarMaterial_funcionaCorrectamente(): void
    {
        $categoria = Categoria::create(['nombre' => 'Categoria Test']);

        $response = $this->postJson('/api/materiales', [
            'unidadMedida' => 'kg',
            'descripcion'  => 'Cemento Portland',
            'ubicacion'    => 'Bodega A',
            'idCategoria'  => $categoria->idCategoria,
        ]);

        $response->assertStatus(201)
                 ->assertJsonFragment(['message' => 'Material creado exitosamente.'])
                 ->assertJsonStructure([
                     'message',
                     'material' => ['codigo', 'unidadMedida', 'descripcion', 'ubicacion', 'idCategoria', 'categoria'],
                 ]);

        $this->assertDatabaseHas('materiales', [
            'descripcion' => 'Cemento Portland',
            'ubicacion'   => 'Bodega A',
        ]);
    }
}
