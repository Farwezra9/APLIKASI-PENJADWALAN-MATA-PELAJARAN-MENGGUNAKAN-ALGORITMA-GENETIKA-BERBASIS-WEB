<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGenerateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('generate_jadwal', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_mengajar');
            $table->unsignedBigInteger('id_hari');
            $table->unsignedBigInteger('id_jam');
            $table->string('kelas');
            $table->timestamps();

            $table->foreign('id_mengajar')->references('id')->on('mengajar')->onDelete('cascade');
            $table->foreign('id_hari')->references('id')->on('hari')->onDelete('cascade');
            $table->foreign('id_jam')->references('id')->on('jam')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('generate_jadwal');
    }
}
