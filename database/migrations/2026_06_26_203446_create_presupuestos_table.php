<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('presupuestos', function (Blueprint $table) {
            $table->increments('codigoPresupuesto');
            $table->string('nombrePresupuesto');
            $table->unsignedInteger('idUnidad');
            $table->timestamps();

            $table->foreign('idUnidad')
                  ->references('idUnidad')
                  ->on('unidades')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presupuestos');
    }
};