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
        Schema::create('barangs', function (Blueprint $table) {
            $table->varchar()->uniqid()->primary();
            $table->string('nama');
            $table->string('merk');
            $table->integer('stok');
            $table->double('harga');
            $table->double('berat');
            $table->text('deskripsi');
            $table->enum('kategori', ['Tenda', 'Bag', 'Perlengkapan Tidur', 'Lampu', 'Alat Masak dan Makan', 'Wears', 'Lainnya']);
            $table->string('image');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barangs');
    }
};
