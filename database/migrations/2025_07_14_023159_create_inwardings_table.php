<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('inwardings', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('raw_material_type_id');
        $table->date('purchase_date');
        $table->unsignedBigInteger('vendor_id');
        $table->integer('number_of_pcs')->nullable();
        $table->timestamps();

        // Foreign key constraints
        $table->foreign('raw_material_type_id')->references('id')->on('raw_material_types')->onDelete('cascade');
        $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade');
    });
}


};
