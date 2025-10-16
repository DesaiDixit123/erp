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
        Schema::table('inspections', function (Blueprint $table) {
              $table->integer('non_filling')->default(0);
        $table->integer('trimming')->default(0);
        $table->integer('casting')->default(0);
        $table->integer('blow_hole')->default(0);
        $table->integer('sound_test')->default(0);
          
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inspections', function (Blueprint $table) {
            //
        });
    }
};
