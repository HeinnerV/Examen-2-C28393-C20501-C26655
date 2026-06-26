<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('requisiciones', function (Blueprint $table) {
            $table->increments('idRequisicion');
            $table->dateTime('fecha');
            $table->string('estado');
            $table->string('idUsuario')->nullable(); 
            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('requisiciones');
    }
};