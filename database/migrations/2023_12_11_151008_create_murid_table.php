<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMuridTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('murid', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nisn');
            $table->string('nama');
            $table->string('jk');
            $table->string('alamat')->nullable();
            $table->string('email')->unique();
            $table->string('notelp')->nullable();
            $table->unsignedBigInteger('id_kelas');
            $table->unsignedBigInteger('id_user');
            $table->timestamps();

            $table->foreign('id_kelas')->references('id')->on('kelas')->onDelete('cascade');
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('murid');
    }
}
