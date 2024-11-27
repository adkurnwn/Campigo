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
            $table->id()->unique();
            $table->string('kode', 10)->nullable()->unique();
            $table->string('nama');
            $table->string('merk');
            $table->integer('stok')->nullable();
            $table->double('harga');
            $table->double('berat')->nullable();
            $table->text('deskripsi');
            $table->enum('kategori', [
                'Tenda',
                'Bag',
                'Perlengkapan Tidur',
                'Lampu',
                'Alat Masak dan Makan',
                'Wears',
                'Lainnya'
            ]);
            $table->integer('kapasitas')->nullable();
            $table->integer('count_disewa')->default(0);
            $table->string('image')->nullable();
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