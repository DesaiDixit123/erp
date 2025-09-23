<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('inspections', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->unsignedBigInteger('tool_type_id');
            $table->integer('quantity_inspected');
            $table->integer('ok_quantity');
            $table->integer('rejected_quantity');
            $table->string('rejection_reason'); // Dropdown: Non-Filling, Trimming, Casting, Blow hole, Sound test
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspections');
    }
};
