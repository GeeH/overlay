<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('checklists', function (Blueprint $table) {
            // Percentage of the theme's own size; 100 draws it as designed.
            $table->integer('scale')->default(100);
        });
    }

    public function down(): void
    {
        Schema::table('checklists', function (Blueprint $table) {
            $table->dropColumn('scale');
        });
    }
};
