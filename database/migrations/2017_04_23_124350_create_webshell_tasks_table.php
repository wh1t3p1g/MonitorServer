<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWebshellTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('webshell_tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('task_id')->unique();//uuid
            $table->integer('hosts_id');
            $table->string('task_name');//not modify sha1
            $table->string('file_path');
            $table->string('except_extension')->nullable();
            $table->string('script_extension');
            $table->string('except_path')->nullable();
            $table->string('type');//1=>fuzzyhash scan 2=>static scan 3=>statistic scan 4=>full scan
            $table->string('mode');//1=>fast scan 2=>full scan
            $table->string('status');// running/done
            $table->text('description');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('webshell_tasks');
    }
}
