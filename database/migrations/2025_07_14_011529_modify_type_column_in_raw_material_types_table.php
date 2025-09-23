<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
    {
        Schema::table('raw_material_types', function (Blueprint $table) {
            // Modify 'type' column to allow null and no default
            $table->enum('type', ['Raw Material', 'Consumable'])->nullable()->default(null)->change();
        });
    }

    public function down(): void
    {
        Schema::table('raw_material_types', function (Blueprint $table) {
            // Revert back to previous default
            $table->enum('type', ['Raw Material', 'Consumable'])->default('Raw Material')->change();
        });
    }
};
