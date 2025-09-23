<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        // Use Schema::table if table already exists
        Schema::table('raw_material_types', function (Blueprint $table) {
            $table->string('measuring_unit')->nullable();
            $table->decimal('opening_stock', 10, 2)->default(0);
            $table->enum('type', ['Raw Material', 'Consumable'])->default('Raw Material');
        });

    }
    public function down(): void
    {
        Schema::dropIfExists('raw_material_types');
    }
};
