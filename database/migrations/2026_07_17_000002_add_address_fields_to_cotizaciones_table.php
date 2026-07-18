<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cotizaciones', function (Blueprint $table) {
            $table->string('origen_calle', 255)->nullable()->after('origen_direccion');
            $table->string('origen_num_exterior', 20)->nullable()->after('origen_calle');
            $table->string('origen_num_interior', 20)->nullable()->after('origen_num_exterior');
            $table->string('origen_colonia', 255)->nullable()->after('origen_num_interior');
            $table->string('origen_codigo_postal', 10)->nullable()->after('origen_colonia');
            $table->string('origen_localidad', 255)->nullable()->after('origen_codigo_postal');
            $table->string('origen_municipio', 255)->nullable()->after('origen_localidad');
            $table->string('origen_estado', 255)->nullable()->after('origen_municipio');

            $table->string('destino_calle', 255)->nullable()->after('destino_direccion');
            $table->string('destino_num_exterior', 20)->nullable()->after('destino_calle');
            $table->string('destino_num_interior', 20)->nullable()->after('destino_num_exterior');
            $table->string('destino_colonia', 255)->nullable()->after('destino_num_interior');
            $table->string('destino_codigo_postal', 10)->nullable()->after('destino_colonia');
            $table->string('destino_localidad', 255)->nullable()->after('destino_codigo_postal');
            $table->string('destino_municipio', 255)->nullable()->after('destino_localidad');
            $table->string('destino_estado', 255)->nullable()->after('destino_municipio');
        });
    }

    public function down(): void
    {
        Schema::table('cotizaciones', function (Blueprint $table) {
            $table->dropColumn([
                'origen_calle', 'origen_num_exterior', 'origen_num_interior',
                'origen_colonia', 'origen_codigo_postal', 'origen_localidad',
                'origen_municipio', 'origen_estado',
                'destino_calle', 'destino_num_exterior', 'destino_num_interior',
                'destino_colonia', 'destino_codigo_postal', 'destino_localidad',
                'destino_municipio', 'destino_estado',
            ]);
        });
    }
};
