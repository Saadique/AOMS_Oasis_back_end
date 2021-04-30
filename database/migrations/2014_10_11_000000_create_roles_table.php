<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('role_name');
            $table->string('table_name');
            $table->string('code');
            $table->string('status')->nullable()->default('active');
            $table->string('description')->nullable();
            $table->timestamps();
        });

//        DB::table('roles')->insert(
//            array(
//                'id' => 1,
//                'role_name' => 'admin',
//                'table_name' => 'admins',
//                'code'=>'admin'
//            ),
//            array(
//                'id' => 2,
//                'role_name' => 'Student',
//                'table_name' => 'students',
//                'code'=>'student'
//            ),
//            array(
//                'id' => 3,
//                'role_name' => 'Teacher',
//                'table_name' => 'teachers',
//                'code'=>'teacher'
//            ),
//            array(
//                'id' => 4,
//                'role_name' => 'Administrative Staff',
//                'table_name' => 'administrative_staff',
//                'code'=>'admin_staff'
//            )
//        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }
}
