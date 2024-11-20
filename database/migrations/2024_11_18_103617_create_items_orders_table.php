<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaksi_sewa_id')->constrained('transaksi_sewas')->onDelete('cascade');
            $table->foreignId('barang_id')->constrained('barangs')->onDelete('cascade');
            $table->integer('quantity');
            $table->double('price_per_item');
            $table->double('subtotal');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items_orders');
    }
};