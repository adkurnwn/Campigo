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
        Schema::create('transaksi_sewas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unsigned();
            $table->double('total_harga')->nullable();
            $table->double('total_denda')->default(0);
            $table->date('tgl_pinjam')->nullable();
            $table->date('tgl_kembali')->nullable();
            $table->enum('status', [
                'pending',
                'pembayaran terkonfirmasi',
                'dibatalkan',
                'selesai',
                'berlangsung',
                'belum bayar',
                'diperiksa',
                'pelunasan',
                'pelunasan diperiksa'
            ]);
            $table->boolean('reminded')->default(false);
            $table->enum('metode_bayar', ['tunai', 'transferbank', 'ewallet', 'qris']);
            $table->enum('metode_bayar_lunas', ['tunai', 'transferbank', 'ewallet', 'qris'])->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_sewas');
    }
};