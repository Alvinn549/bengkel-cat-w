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
        Schema::create('progres', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perbaikan_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('pekerja_id')->nullable()->constrained()->onDelete('set null');
            $table->text('keterangan')->nullable();
            $table->string('foto')->nullable();
            $table->boolean('is_selesai')->default(0);
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
        Schema::dropIfExists('progres');
    }
};
