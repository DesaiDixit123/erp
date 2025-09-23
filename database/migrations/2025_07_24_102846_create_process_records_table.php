<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('process_records', function (Blueprint $table) {
            $table->id();

            $table->enum('type_name', ['Casting', 'Trimming', 'Inspection', 'Dispatch']);

            $table->date('date');
            $table->enum('shift', ['Day', 'Night']);
            $table->string('machine_no');
            $table->unsignedBigInteger('tool_type_id'); // Foreign key (you can modify this)
            $table->string('machine_operator_name');
            $table->decimal('quantity_kg', 10, 2)->nullable();
            $table->integer('quantity_pcs')->nullable();

            // Inspection-related
            $table->integer('quantity_inspected')->nullable();
            $table->integer('ok_number')->nullable();
            $table->integer('rejected_number')->nullable();
            $table->string('reason_of_rejected')->nullable();

            // Dispatch-related
            $table->integer('quantity_dispatch')->nullable();

            $table->timestamps();

            // Foreign key constraint (if needed)
            $table->foreign('tool_type_id')->references('id')->on('tools')->onDelete('cascade');

        });
    }

    public function down()
    {
        Schema::dropIfExists('process_records');
    }
};
