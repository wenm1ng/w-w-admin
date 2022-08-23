<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMonitorSummaryListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('monitor_summary_lists', function (Blueprint $table) {
            $table->comment = '计划监控列表';
            $table->increments('id')->comment('id');
            $table->string('plat_name',100)->default('')->comment('平台名称');
            $table->integer('plat_id')->default(0)->comment('平台id');
            $table->string('name',100)->default('')->comment('账号名称');
            $table->integer('memberid')->default(0)->comment('账号ID');

            $table->string('campaignname',100)->default('')->comment('计划组名称');
            $table->integer('campaignId')->default(0)->comment('计划组ID');
            $table->string('plan_name',100)->default('')->comment('计划名称');
            $table->integer('plan_id')->default(0)->comment('计划ID');
            $table->string('budget',100)->default('')->comment('预算');
            $table->decimal('cpabid',10,2)->comment('出价');
            $table->decimal('collectioncost',10,2)->comment('消耗');
            $table->decimal('ecpm',10,2)->comment('千次展现成本');
            $table->decimal('adctr',10,2)->comment('点击率');
            $table->decimal('ecpc',10,2)->comment('点击单价');
            $table->integer('adpv')->default(0)->comment('展现量');
            $table->integer('click')->default(0)->comment('点击量');
            $table->decimal('returnoninvestment',10,2)->comment('投资回报率(roi)');

            $table->integer('takeordervolume')->default(0)->comment('拍下订单量');
            $table->integer('transactionvolume')->default(0)->comment('成交订单量');
            $table->decimal('transactionamount',10,2)->comment('成交订单金额');
            $table->decimal('alipaycost',10,2)->comment('订单成本');
            $table->decimal('takeorderamount',10,2)->comment('拍下订单金额');

            $table->integer('pagearrive')->default(0)->comment('转化数');
            $table->decimal('convertcost',10,2)->comment('转化成本');
            $table->decimal('addcartvolume',10,2)->comment('添加购物车量');
            $table->string('key',32)->default('')->comment('每次请求唯一值');

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
        Schema::dropIfExists('monitor_summary_lists');
    }
}
