<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMonitorTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('monitor_tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('task_name')->unique();//not modify sha1
            $table->integer("hosts_id");
            $table->string('project_name');
            $table->string('monitor_path');
            $table->string('white_list');
            $table->string('black_list');
            $table->string('status');
            $table->string('bc_mode')->default("1");//0 备份模式；1 自检模式
            $table->string('run_mode');//0 停止；1 人工模式；2 防篡改模式； (3 扫描；4 返回路径 暂定)
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
        Schema::dropIfExists('monitor_tasks');
    }
}
