<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('material_issueds', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('raw_material_type_id');
            $table->date('issue_date');
            $table->integer('quantity');
            $table->enum('shift', ['Day', 'Night']);

            $table->timestamps();

            $table->foreign('raw_material_type_id')->references('id')->on('raw_material_types')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('material_issueds');
    }
};
