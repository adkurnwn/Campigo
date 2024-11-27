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
        Schema::create('items_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaksi_sewa_id')->unsigned();
            $table->foreignId('barang_id');
            $table->integer('quantity')->nullable();
            $table->double('price_per_day')->nullable();
            $table->double('subtotal')->nullable();
            $table->json('kondisi_kembali')->nullable();
            $table->double('denda')->default(0);
            $table->timestamps();

            $table->foreign('transaksi_sewa_id')->references('id')->on('transaksi_sewas');
            $table->foreign('barang_id')->references('id')->on('barangs');
            $table->index('barang_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items_orders');
    }
};