<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCallsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(){
		Schema::create('calls', function ($table) {
			$table->increments('id');
			$table->string('CallSid')->unique();
			$table->string('AccountSid');
			$table->string('CallFrom');
			$table->string('CallTo');
			$table->string('CallStatus');
			$table->string('ApiVersion');
			$table->string('Direction');
			$table->string('FromCity');
			$table->string('FromState');
			$table->string('FromZip');
			$table->string('FromCountry');
			$table->string('ToCity');
			$table->string('ToState');
			$table->string('ToZip');
			$table->string('ToCountry');
			$table->string('DateCreated');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		Schema::drop('calls');
	}

}
