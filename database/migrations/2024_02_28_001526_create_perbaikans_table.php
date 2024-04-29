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
            $table->foreignId('kendaraan_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('kode_unik')->nullable();
            $table->string('nama')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('foto')->nullable();
            $table->integer('biaya')->nullable();
            $table->string('durasi')->nullable();
            $table->dateTime('tgl_selesai')->nullable();
            $table->enum('status', ['Baru', 'Antrian', 'Dalam Proses', 'Proses Selesai', 'Menunggu Bayar', 'Selesai'])->nullable();
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
