<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::create('employees', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('email')->unique();
        $table->string('phone');
        $table->string('address');
        $table->string('password');
        $table->string('avatar')->nullable();
        $table->string('adhar_image')->nullable();
        $table->string('pan_image')->nullable();
        $table->enum('user_type', ['Admin', 'Operator'])->default('Operator');
        $table->enum('user_status', ['Active', 'Inactive'])->default('Inactive');
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
