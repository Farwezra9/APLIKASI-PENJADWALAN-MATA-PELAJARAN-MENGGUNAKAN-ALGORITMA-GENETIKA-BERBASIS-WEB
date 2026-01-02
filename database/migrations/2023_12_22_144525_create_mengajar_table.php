<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMengajarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mengajar', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_pel');
            $table->unsignedBigInteger('id_guru');
            $table->longText('kelas');
            $table->integer('sks');
            $table->string('semester');
            $table->timestamps();
            
            $table->foreign('id_pel')->references('id')->on('mata_pelajaran')->onDelete('cascade');
            $table->foreign('id_guru')->references('id')->on('guru')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mengajar');
    }
}
