<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableClubUserInvitation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clubs_users_invitations', function (Blueprint $table) {
            $table->unsignedBigInteger('user');
            $table->unsignedBigInteger('club');
            $table->timestamps();
            $table->index(['user', 'club']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clubs_users_invitations', function (Blueprint $table) {
            Schema::dropIfExists('clubs_users_invitations');
        });
    }
}
