<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\Material;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MaterialControllerTest extends TestCase
{
    use RefreshDatabase;

    private function crearCategoria(): Categoria
    {
        return Categoria::create(['nombre' => 'Categoria Test']);
    }

    // ─── store ───────────────────────────────────────────────────────────────

    public function dadoUnMaterialQueNoExiste_insertarMaterial_funcionaCorrectamente(): void
    {
        $categoria = $this->crearCategoria();

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

    public function test_store_crea_material_exitosamente(): void
    {
        $categoria = $this->crearCategoria();

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

    public function test_store_falla_sin_campos_requeridos(): void
    {
        $response = $this->postJson('/api/materiales', []);

        $response->assertStatus(422)
                 ->assertJsonFragment(['message' => 'Error de validación.'])
                 ->assertJsonStructure(['message', 'errors']);
    }

    public function test_store_falla_con_categoria_inexistente(): void
    {
        $response = $this->postJson('/api/materiales', [
            'unidadMedida' => 'kg',
            'descripcion'  => 'Cemento',
            'ubicacion'    => 'Bodega A',
            'idCategoria'  => 9999,
        ]);

        $response->assertStatus(422)
                 ->assertJsonFragment(['message' => 'Error de validación.']);
    }

    public function test_store_falla_con_unidadMedida_demasiado_larga(): void
    {
        $categoria = $this->crearCategoria();

        $response = $this->postJson('/api/materiales', [
            'unidadMedida' => str_repeat('a', 256),
            'descripcion'  => 'Descripcion',
            'ubicacion'    => 'Bodega A',
            'idCategoria'  => $categoria->idCategoria,
        ]);

        $response->assertStatus(422);
    }

    // ─── update ──────────────────────────────────────────────────────────────

    public function test_update_modifica_material_exitosamente(): void
    {
        $categoria = $this->crearCategoria();
        $material  = Material::create([
            'unidadMedida' => 'kg',
            'descripcion'  => 'Cemento',
            'ubicacion'    => 'Bodega A',
            'idCategoria'  => $categoria->idCategoria,
        ]);

        $response = $this->putJson("/api/materiales/{$material->codigo}", [
            'ubicacion' => 'Bodega B',
        ]);

        $response->assertStatus(200)
                 ->assertJsonFragment(['message' => 'Material actualizado exitosamente.'])
                 ->assertJsonPath('material.ubicacion', 'Bodega B');

        $this->assertDatabaseHas('materiales', [
            'codigo'    => $material->codigo,
            'ubicacion' => 'Bodega B',
        ]);
    }

    public function test_update_retorna_404_si_material_no_existe(): void
    {
        $response = $this->putJson('/api/materiales/9999', [
            'ubicacion' => 'Bodega C',
        ]);

        $response->assertStatus(404)
                 ->assertJsonFragment(['message' => 'Material no encontrado.']);
    }

    public function test_update_falla_con_categoria_inexistente(): void
    {
        $categoria = $this->crearCategoria();
        $material  = Material::create([
            'unidadMedida' => 'kg',
            'descripcion'  => 'Cemento',
            'ubicacion'    => 'Bodega A',
            'idCategoria'  => $categoria->idCategoria,
        ]);

        $response = $this->putJson("/api/materiales/{$material->codigo}", [
            'idCategoria' => 9999,
        ]);

        $response->assertStatus(422)
                 ->assertJsonFragment(['message' => 'Error de validación.']);
    }

    public function test_update_actualiza_todos_los_campos(): void
    {
        $categoria1 = $this->crearCategoria();
        $categoria2 = Categoria::create(['nombre' => 'Categoria 2']);
        $material   = Material::create([
            'unidadMedida' => 'kg',
            'descripcion'  => 'Cemento',
            'ubicacion'    => 'Bodega A',
            'idCategoria'  => $categoria1->idCategoria,
        ]);

        $response = $this->putJson("/api/materiales/{$material->codigo}", [
            'unidadMedida' => 'litros',
            'descripcion'  => 'Pintura',
            'ubicacion'    => 'Bodega C',
            'idCategoria'  => $categoria2->idCategoria,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('materiales', [
            'codigo'       => $material->codigo,
            'unidadMedida' => 'litros',
            'descripcion'  => 'Pintura',
            'ubicacion'    => 'Bodega C',
            'idCategoria'  => $categoria2->idCategoria,
        ]);
    }

    // ─── index ───────────────────────────────────────────────────────────────

    public function test_index_retorna_lista_de_materiales(): void
    {
        $categoria = $this->crearCategoria();
        Material::create([
            'unidadMedida' => 'kg',
            'descripcion'  => 'Cemento',
            'ubicacion'    => 'Bodega A',
            'idCategoria'  => $categoria->idCategoria,
        ]);
        Material::create([
            'unidadMedida' => 'litros',
            'descripcion'  => 'Pintura',
            'ubicacion'    => 'Bodega B',
            'idCategoria'  => $categoria->idCategoria,
        ]);

        $response = $this->getJson('/api/materiales');

        $response->assertStatus(200)
                 ->assertJsonFragment(['message' => 'Lista de materiales obtenida exitosamente.'])
                 ->assertJsonStructure([
                     'message',
                     'materiales' => [
                         '*' => ['codigo', 'unidadMedida', 'descripcion', 'ubicacion', 'idCategoria', 'categoria'],
                     ],
                 ]);

        $this->assertCount(2, $response->json('materiales'));
    }

    public function test_index_retorna_lista_vacia_cuando_no_hay_materiales(): void
    {
        $response = $this->getJson('/api/materiales');

        $response->assertStatus(200)
                 ->assertJsonFragment(['message' => 'Lista de materiales obtenida exitosamente.'])
                 ->assertJsonPath('materiales', []);
    }

    public function test_index_incluye_relacion_categoria(): void
    {
        $categoria = $this->crearCategoria();
        Material::create([
            'unidadMedida' => 'kg',
            'descripcion'  => 'Cemento',
            'ubicacion'    => 'Bodega A',
            'idCategoria'  => $categoria->idCategoria,
        ]);

        $response = $this->getJson('/api/materiales');

        $response->assertStatus(200);
        $primerMaterial = $response->json('materiales.0');
        $this->assertArrayHasKey('categoria', $primerMaterial);
        $this->assertEquals('Categoria Test', $primerMaterial['categoria']['nombre']);
    }
}
