<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMstLoginAuditReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mst_login_aduit_reports', function (Blueprint $table) {
            $table->id('audit_report_id');
            $table->bigInteger('user_id');
            $table->date('login_date')->nullable();
            $table->time('login_time')->nullable();
            $table->date('logout_date')->nullable();
            $table->time('logout_time')->nullable();
            $table->string('status', 500);
            $table->bigInteger('created_by')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->bigInteger('updated_by')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->string('flag', 45)->default('show');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mst_login_aduit_reports');
    }
}
