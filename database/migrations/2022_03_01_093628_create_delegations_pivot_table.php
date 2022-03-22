<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDelegationsPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_delegations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('delegator_user_id')->unsigned();
            $table->integer('delegate_user_id')->unsigned();
            $table->dateTime('starting')->useCurrent();
            $table->dateTime('until')->nullable();
            $table->boolean('gets_reminders')->default(false);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();

            $table->foreign('delegator_user_id')
                    ->references('id')->on('users');
            $table->foreign('delegate_user_id')
                    ->references('id')->on('users');
            
            $table->index(['delegator_user_id', 'delegate_user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_delegations');
    }
}
