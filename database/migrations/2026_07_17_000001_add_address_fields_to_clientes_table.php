<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->string('calle', 255)->nullable()->after('direccion');
            $table->string('num_exterior', 20)->nullable()->after('calle');
            $table->string('num_interior', 20)->nullable()->after('num_exterior');
            $table->string('colonia', 255)->nullable()->after('num_interior');
            $table->string('codigo_postal', 10)->nullable()->after('colonia');
            $table->string('localidad', 255)->nullable()->after('codigo_postal');
            $table->string('municipio', 255)->nullable()->after('localidad');
            $table->string('estado', 255)->nullable()->after('municipio');
        });
    }

    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropColumn(['calle', 'num_exterior', 'num_interior', 'colonia', 'codigo_postal', 'localidad', 'municipio', 'estado']);
        });
    }
};
