<?php namespace Waka\Mailer\Updates;

use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;
use Schema;

class AddWakaMailsTableU102 extends Migration
{
    public function up()
    {
        if (Schema::hasTable('waka_mailer_wakamails')) {
            Schema::table('waka_mailer_wakamails', function (Blueprint $table) {
                $table->boolean('use_lp')->nullable();
                $table->string('lp')->nullable();
                $table->boolean('use_key')->nullable();
                $table->string('key_duration')->nullable();

            });
        }

    }

    public function down()
    {
        if (Schema::hasTable('waka_mailer_wakamails')) {
            Schema::table('waka_mailer_wakamails', function (Blueprint $table) {
                $table->dropColumn('use_lp');
                $table->dropColumn('lp');
                $table->dropColumn('use_key');
                $table->dropColumn('key_duration');
            });
        }
    }
}
