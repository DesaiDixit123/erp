<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('process_records', function (Blueprint $table) {
            $table->string('type_name', 20)->change(); // 20 chars enough
        });
    }

    public function down()
    {
        Schema::table('process_records', function (Blueprint $table) {
            $table->string('type_name', 10)->change(); // revert if needed
        });
    }
};
