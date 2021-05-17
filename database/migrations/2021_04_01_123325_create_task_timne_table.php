<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaskTimneTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task_time', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer("taskIdB24");
            $table->integer("id_user");
            $table->text("name_user");
            $table->integer("timestamp_plan");
            $table->integer("timestamp_fact");
            $table->date("date");
            $table->text("comments_plan");
            $table->text("comments_fact");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('task_timne');
    }
}
