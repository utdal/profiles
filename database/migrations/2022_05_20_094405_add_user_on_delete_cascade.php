<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserOnDeleteCascade extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropForeign('profiles_user_id_foreign');
            $table->foreign('user_id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade');
        });

        Schema::table('profile_data', function (Blueprint $table) {
            $table->dropForeign('profile_data_profile_id_foreign');
            $table->foreign('profile_id')
                    ->references('id')
                    ->on('profiles')
                    ->onDelete('cascade');
        });

        Schema::table('user_delegations', function (Blueprint $table) {
            $table->dropForeign('user_delegations_delegate_user_id_foreign');
            $table->foreign('delegate_user_id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade');

            $table->dropForeign('user_delegations_delegator_user_id_foreign');
            $table->foreign('delegator_user_id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropForeign('profiles_user_id_foreign');
            $table->foreign('user_id')
                    ->references('id')
                    ->on('users');
        });

        Schema::table('profile_data', function (Blueprint $table) {
            $table->dropForeign('profile_data_profile_id_foreign');
            $table->foreign('profile_id')
                    ->references('id')
                    ->on('profiles');
        });

        Schema::table('user_delegations', function (Blueprint $table) {
            $table->dropForeign('user_delegations_delegate_user_id_foreign');
            $table->foreign('delegate_user_id')
                    ->references('id')
                    ->on('users');

            $table->dropForeign('user_delegations_delegator_user_id_foreign');
            $table->foreign('delegator_user_id')
                    ->references('id')
                    ->on('users');
        });
    }
}