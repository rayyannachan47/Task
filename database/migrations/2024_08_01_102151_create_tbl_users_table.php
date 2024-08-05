<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateTblUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_users', function (Blueprint $table) {
            $table->id('user_id');
            $table->string('name', 50);
            $table->string('email', 50);
            $table->text('password');
            $table->string('dob', 50)->default('');
            $table->string('contact', 50);
            $table->string('address', 300);
            $table->string('image', 500)->nullable();
            $table->string('role_id', 10);
            $table->date('created_date');
            $table->time('created_time');
            $table->date('updated_date')->nullable();
            $table->time('updated_time')->nullable();
            $table->string('flag', 10)->default('Show');
        });

        date_default_timezone_set('Asia/Kolkata');
        
        DB::table('tbl_users')->insert([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => 'eyJpdiI6IktBOEljb05DL0ZQeml0NEtieHhZQ2c9PSIsInZhbHVlIjoiZitJNWlOb1FjUUxwM3pkN3ZxTFk0Zz09IiwibWFjIjoiNDJhMDYxNmZmY2YzYTE1NWI5NmZmYjE1MjRlNGYyYTRkZGM5ODQ4ZmUzNjg2MWE0MDk5ODcwMGFiODU4Yzc5NyIsInRhZyI6IiJ9',
            'dob' => '03-07-1995',
            'contact' => '9137680110',
            'address' => 'Kalyan',
            'role_id' => '1',
            'created_date' => now()->format('Y-m-d'),
            'created_time' => now()->format('H:i:s'),
            'flag' => 'Show',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_users');
    }
}
