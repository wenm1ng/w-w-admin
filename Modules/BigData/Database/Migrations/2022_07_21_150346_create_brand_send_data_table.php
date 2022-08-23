<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBrandSendDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('brand_send_datas', function (Blueprint $table) {
            $table->comment = '预警日志表';
            $table->increments('id')->comment('id');
            $table->integer('brand_id')->default(0)->comment('数据ID');
            $table->string('name',100)->default('')->comment('预警名称');
            $table->string('key',100)->default('')->comment('字段');
            $table->string('keys',100)->default('')->comment('唯一key值');
            $table->integer('values')->default(0)->comment('预警值');
            $table->tinyInteger('state')->default(1)->comment('状态');
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
        Schema::dropIfExists('brand_send_data');
    }
}
