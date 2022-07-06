<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('user_settings', 'no_sync')) {
            Schema::table('user_settings', function (Blueprint $table) {
                $table->json('no_sync')->after('user_id')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('user_settings', 'no_sync')) {
            Schema::table('user_settings', function (Blueprint $table) {
                $table->dropColumn('no_sync');
            });
        }
    }
};
