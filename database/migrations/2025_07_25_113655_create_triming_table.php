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
        Schema::create('trimming_records', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('shift'); // Day/Night
            $table->integer('machine_no'); // 1,2,3,5,6

            // 👇 First create the column
            $table->unsignedBigInteger('tool_type_id');

            // 👇 Then define the foreign key constraint
            $table->foreign('tool_type_id')->references('id')->on('tools')->onDelete('cascade');

         
            $table->string('operator_name')->nullable();
            $table->float('quantity_kg');
            $table->integer('quantity_pcs');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('triming');
    }
};
