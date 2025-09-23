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
        Schema::create('tools_dispatches', function (Blueprint $table) {
            $table->id();

    
            $table->unsignedBigInteger('component_id');

            // Tool type dropdown
            $table->enum('component_type', ['Casting Tool', 'Trimming Tool'])->nullable();

            // Tool number (text)
            $table->string('tool_number')->nullable();

            // Manufacturing date
            $table->dateTime('manufacturing_date')->nullable();

            // Quantity dispatch
            $table->integer('quantity_dispatch')->default(0);

            $table->timestamps();

            // Foreign key (assuming component_id refers to tools.id)
            $table->foreign('component_id')->references('id')->on('tools')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tools_dispatches');
    }
};
