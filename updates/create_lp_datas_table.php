<?php namespace Waka\Lp\Updates;

use Winter\Storm\Database\Schema\Blueprint;
use Winter\Storm\Database\Updates\Migration;
use Schema;

class CreateLpDatasTable extends Migration
{
    public function up()
    {
        Schema::create('waka_lp_lp_datas', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->string('lpeable_id')->nullable();
            $table->string('lpeable_type')->nullable();
            $table->string('url')->nullable();
            $table->boolean('use_key')->nullable();
            $table->string('key_duration')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('waka_lp_lp_datas');
    }
}