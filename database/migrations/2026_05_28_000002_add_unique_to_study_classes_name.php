<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambahkan unique constraint pada study_classes.name
     * untuk mencegah duplikasi kelas (misal: dua kelas bernama "A").
     */
    public function up(): void
    {
        Schema::table('study_classes', function (Blueprint $table) {
            $table->unique('name');
        });
    }

    public function down(): void
    {
        Schema::table('study_classes', function (Blueprint $table) {
            $table->dropUnique(['name']);
        });
    }
};
