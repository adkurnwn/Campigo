
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('transaksi_sewas', function (Blueprint $table) {
            if (!Schema::hasColumn('transaksi_sewas', 'status')) {
                $table->string('status')->default('belum_bayar');
            }
        });
    }

    public function down()
    {
        Schema::table('transaksi_sewas', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};