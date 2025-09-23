<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
{
    Schema::table('inwardings', function (Blueprint $table) {
        $table->integer('quantity')->after('number_of_pcs')->nullable(); // or ->default(0)
    });
}

public function down()
{
    Schema::table('inwardings', function (Blueprint $table) {
        $table->dropColumn('quantity');
    });
}

};
