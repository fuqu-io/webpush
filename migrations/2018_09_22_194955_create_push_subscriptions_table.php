<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePushSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('push_subscriptions', function (Blueprint $table) {
            $table->increments('id');

	        $table->char('pushable_id');
	        $table->char('pushable_type');

            $table->string('endpoint', 500);
            $table->string('public_key')->nullable();
            $table->string('auth_token')->nullable();

            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('push_subscriptions');
    }
}
