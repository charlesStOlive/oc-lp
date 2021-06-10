<?php namespace Waka\Mailtoer\Updates;

use Winter\Storm\Database\Schema\Blueprint;
use Winter\Storm\Database\Updates\Migration;
use Schema;

class AddWakaMailtosTableU103 extends Migration
{
    public function up()
    {
        if (Schema::hasTable('waka_mailtoer_waka_mailtos')) {
            if (!Schema::hasColumn('waka_mailtoer_waka_mailtos', 'use_lp')) {
                Schema::table('waka_mailtoer_waka_mailtos', function (Blueprint $table) {
                    $table->boolean('use_lp')->nullable();
                    $table->string('lp')->nullable();
                    $table->boolean('use_key')->nullable();
                    $table->string('key_duration')->nullable();
                });
            }
        }
    }

    public function down()
    {
        if (Schema::hasColumn('waka_mailtoer_waka_mailtos', 'use_lp')) {
            Schema::table('waka_mailtoer_waka_mailtos', function (Blueprint $table) {
                $table->dropColumn('use_lp');
                $table->dropColumn('lp');
                $table->dropColumn('use_key');
                $table->dropColumn('key_duration');
            });
        }
    }
}
