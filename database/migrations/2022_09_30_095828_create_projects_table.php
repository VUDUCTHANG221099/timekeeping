<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            // $table->date("start_project");
            // $table->date("end_project")->nullable();
            $table->text("description")->nullable();
            $table->enum("status",[0,1,2])->comment("0: start,1 pending,2 closed");
            // $table->string("cost")->default("0");//Add
            // $table->string("menber")->default("0");//Add
            $table->timestamps();
            // $table->softDeletes();
        });
        Schema::create('employee_project', function (Blueprint $table) {
            $table->id();
            $table->date("day_work");
            $table->float("working_hours");
            $table->enum("status_employee_project",[0,1])->comment("0: no in project,1 in project");
            $table->bigInteger("user_id")->unsigned();
            $table->foreign("user_id")->references("id")->on("users")->onDelete("cascade")->onUpdate("cascade");
            $table->bigInteger("project_id")->unsigned();
            $table->foreign("project_id")->references("id")->on("projects")->onDelete("cascade")->onUpdate("cascade");
        });
        Schema::create('plan', function (Blueprint $table) {
            $table->id();
            $table->integer("plan")->comment("Kế hoạch")->unsigned()->nullable();
            // $table->integer("reality")->comment("Thực tế")->unsigned()->nullable();
            $table->enum("status_plan",[0,1])->comment("0: no in project,1 in project");
            $table->date("day_addEmp");
            $table->bigInteger("user_id")->unsigned();
            $table->foreign("user_id")->references("id")->on("users")->onDelete("cascade")->onUpdate("cascade");
            $table->bigInteger("project_id")->unsigned();
            $table->foreign("project_id")->references("id")->on("projects")->onDelete("cascade")->onUpdate("cascade");
            $table->json("roles")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projects');
        Schema::dropIfExists('employee_project');
        Schema::dropIfExists('plan');

    }
}
