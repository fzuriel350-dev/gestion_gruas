<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('autorizaciones_cancelacion', function (Blueprint $table) {
            $table->foreignId('empresa_id')->nullable()->after('id')->constrained('empresas')->nullOnDelete();
        });

        DB::statement('
            UPDATE autorizaciones_cancelacion ac
            JOIN servicios s ON ac.servicio_id = s.id
            SET ac.empresa_id = s.empresa_id
            WHERE ac.empresa_id IS NULL
        ');
    }

    public function down(): void
    {
        Schema::table('autorizaciones_cancelacion', function (Blueprint $table) {
            $table->dropForeign(['empresa_id']);
            $table->dropColumn('empresa_id');
        });
    }
};
