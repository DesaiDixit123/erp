<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('available_raw_materials', function (Blueprint $table) {
    $table->id();
    $table->foreignId('raw_material_type_id')->constrained()->onDelete('cascade');
    $table->integer('quantity');
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('available_raw_materials');
    }
};
