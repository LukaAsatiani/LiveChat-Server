<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoomUserConnectorsTable extends Migration{
    public function up(){
        Schema::create('room_user_connectors', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('room_id');
            $table->unsignedInteger('unread_count');
            $table->timestamps();
        });
    }

    public function down(){
        Schema::dropIfExists('room_user_connectors');
    }
}
