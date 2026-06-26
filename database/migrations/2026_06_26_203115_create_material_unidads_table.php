<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('material_unidad', function (Blueprint $table) {
            $table->increments('idMaterialUnidad');
            $table->integer('cantidad');
            $table->unsignedInteger('idUnidad');
            $table->unsignedInteger('codigo');           // FK → materiales
            $table->unsignedInteger('codigoPresupuesto')->nullable(); // FK → presupuestos (0..*)
            $table->timestamps();

            $table->foreign('idUnidad')
                  ->references('idUnidad')
                  ->on('unidades')
                  ->onDelete('cascade');

            $table->foreign('codigo')
                  ->references('codigo')
                  ->on('materiales')
                  ->onDelete('cascade');

            $table->foreign('codigoPresupuesto')
                  ->references('codigoPresupuesto')
                  ->on('presupuestos')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('material_unidad');
    }
};