<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoreTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store', function (Blueprint $table) {
            $table->comment = '店铺表';
            $table->increments('id')->comment('店铺ID');
            $table->string('name',100)->default('')->comment('店铺名称');
            $table->string('number')->nullable()->comment('店铺编号');
            $table->string('url',100)->default('')->comment('店铺地址');
            $table->tinyInteger('plat_type')->default(0)->comment('平台:0=未知,1=天猫，2=京东');
            $table->tinyInteger('status')->default(1)->comment('状态:0=禁用,1=启用');
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
        Schema::dropIfExists('store');
    }
}
