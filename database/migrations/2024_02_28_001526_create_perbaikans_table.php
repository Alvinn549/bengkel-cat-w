<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('perbaikans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kendaraan_id')->nullable()->constrained();
            $table->string('nama')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('foto')->nullable();
            $table->decimal('biaya', 10, 2)->nullable();
            $table->string('durasi')->nullable();
            $table->dateTime('tgl_selesai')->nullable();
            $table->enum('status', ['Selesai', 'Dalam Proses', 'Ditunda', 'Dibatalkan', 'Tidak Dapat Diperbaiki'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('perbaikans');
    }
};
