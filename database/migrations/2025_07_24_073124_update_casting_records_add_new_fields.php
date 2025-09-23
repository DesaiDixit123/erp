<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('casting_records', function (Blueprint $table) {
            $table->date('date')->nullable();
            $table->string('shift')->nullable(); // Day/Night
            $table->string('machine_operator_name')->nullable();
            $table->decimal('quantity_kg', 8, 2)->nullable();
            $table->renameColumn('quantity', 'quantity_pcs'); // Rename quantity to quantity_pcs
        });
    }

    public function down()
    {
        Schema::table('casting_records', function (Blueprint $table) {
            $table->dropColumn(['date', 'shift', 'machine_operator_name', 'quantity_kg']);
            $table->renameColumn('quantity_pcs', 'quantity'); // Revert rename
        });
    }
};
