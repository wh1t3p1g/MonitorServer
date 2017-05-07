<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hosts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ip');//create not update
            $table->string('delay')->default("1");
            $table->string('port')->nullable();//create not update
            $table->string('storage_path')->nullable();//create not update
            $table->string('web_root_path')->nullable();
            $table->text('data')->nullable();//create not update
            $table->string('key');//create not update
            $table->string('iv');//create not update
            $table->timestamp('updated_at')->nullable();//heart beat
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hosts');
    }
}
