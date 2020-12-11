<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserIdToHobbiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hobbies', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')  // unsigned = only positive values; BigInteger = 18 quintillion
            ->after('id')
            ->nullable();
            $table->foreign('user_id')  // make user_id a foreign key, point to (reference) id in users table
                  ->references('id')->on('users')
                  ->onDelete('cascade');  // also delete hobbies when user gets deleted
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()  // used for rollback; php artisan migrate:rollback
    {
        Schema::table('hobbies', function (Blueprint $table) {
            $table->dropForeign(['user_id']); // must use [] !
            $table->dropColumn('user_id');
        });
    }
}
