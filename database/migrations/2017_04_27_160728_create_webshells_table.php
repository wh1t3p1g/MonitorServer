<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWebshellsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('webshells', function (Blueprint $table) {
            $table->increments('id');
            $table->integer("hosts_id");
            $table->string('filename');
            $table->string('fullpath');
            $table->string("hash")->nullable();
            $table->string('poc');//成功的标示
            $table->string('tasks_id');
            $table->string('status');//0=>undo 1=>updated 2=>deleted 3=>add to whiteList
            $table->string('time');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('webshells');
    }
}
