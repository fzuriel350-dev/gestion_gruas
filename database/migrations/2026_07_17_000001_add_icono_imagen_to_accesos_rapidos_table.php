<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('accesos_rapidos', function (Blueprint $table) {
            $table->string('icono_imagen', 255)->nullable()->after('icono');
        });
    }

    public function down(): void
    {
        Schema::table('accesos_rapidos', function (Blueprint $table) {
            $table->dropColumn('icono_imagen');
        });
    }
};
