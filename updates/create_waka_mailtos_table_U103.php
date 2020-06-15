<?php namespace Waka\Mailtoer\Updates;

use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;
use Schema;

class CreateWakaMailtosTableU103 extends Migration
{
    public function up()
    {
        Schema::table('waka_mailtoer_waka_mailtos', function (Blueprint $table) {
            $table->boolean('use_lp')->nullable();
            $table->string('lp')->nullable();
            $table->boolean('use_key')->nullable();
            $table->string('key_duration')->nullable();

        });
    }

    public function down()
    {
        Schema::table('waka_mailtoer_waka_mailtos', function (Blueprint $table) {
            $table->dropColumn('use_lp');
            $table->dropColumn('lp');
            $table->dropColumn('use_key');
            $table->dropColumn('key_duration');
        });
    }
}
