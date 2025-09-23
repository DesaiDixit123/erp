<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
 public function up()
{
    Schema::create('casting_records', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('machine_no'); // 1, 2, or 3
        $table->unsignedBigInteger('company_id');
        $table->unsignedBigInteger('tool_type_id');
        $table->integer('quantity');
        $table->timestamps();

        // Foreign keys (optional if related tables exist)
        $table->foreign('company_id')->references('id')->on('companies');
        $table->foreign('tool_type_id')->references('id')->on('tools');
    });
}

};
