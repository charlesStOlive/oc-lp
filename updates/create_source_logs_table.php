<?php namespace Waka\Lp\Updates;

use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;
use Schema;

class CreateDatasourceLogsTable extends Migration
{
    public function up()
    {
        Schema::create('waka_lp_source_logs', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('visites')->unsigned()->nullable();
            $table->integer('sendeable_id')->unsigned()->nullable();
            ;
            $table->string('sendeable_type')->nullable();
            $table->integer('send_targeteable_id')->unsigned()->nullable();
            ;
            $table->string('send_targeteable_type')->nullable();
            $table->text('events')->nullable();
            $table->text('datas')->nullable();
            $table->string('key')->nullable();
            $table->string('landing_page')->nullable();
            $table->boolean('user_delete_key')->default(false);
            $table->dateTime('end_key_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('waka_lp_source_logs');
    }
}
