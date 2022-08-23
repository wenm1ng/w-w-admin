<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMonitorSummarySettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('monitor_summary_settings', function (Blueprint $table) {
            $table->comment = '报警规则表';
            $table->increments('id')->comment('id');
            $table->integer('user_id')->default(0)->comment('用户ID');
            $table->string('name',100)->default('')->comment('用户');
            $table->integer('group_id')->default(0)->comment('用户组');

            $table->decimal('returnoninvestment',10,2)->comment('投资回报率(roi)');
            $table->decimal('collectioncost',10,2)->comment('消耗');
            $table->decimal('balance',10,2)->comment('余额提醒');
            $table->string('budget',100)->default('')->comment('消耗/预算');
            $table->string('plan',100)->default('')->comment('计划');

            $table->string('key_sign',32)->default('')->comment('预警开启/关闭');

            $table->timestamp('created_at')->nullable()->comment('创建时间');
            $table->timestamp('updated_at')->nullable()->comment('更新时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('monitor_summary_settings');
    }
}
