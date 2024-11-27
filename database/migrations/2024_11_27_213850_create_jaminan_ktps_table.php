<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('jaminan_ktps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaksi_sewa_id')->unsigned();
            $table->string('image_path');
            $table->timestamps();

            $table->foreign('transaksi_sewa_id')->references('id')->on('transaksi_sewas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jaminan_ktps');
    }
};