<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\TransaksiSewa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use to;

class TransaksiSewaTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $transaction;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        
        // Set specific dates for testing
        $this->transaction = TransaksiSewa::create([
            'user_id' => $this->user->id,
            'total_harga' => 100000,
            'tgl_pinjam' => now()->format('Y-m-d'),
            'tgl_kembali' => now()->format('Y-m-d'),
            'status' => 'berlangsung',
            'metode_bayar' => 'transferbank',
        ]);
    }

    public function test_tidak_didenda_sebelum_deadline()
    {
        // Set time to 21:59 on return day
        $this->travel(-1)->hours();
        $this->travelTo(now()->setTime(21, 59));

        $this->transaction->refresh();
        $penalty = $this->transaction->calculateLatePenalty();
        
        $this->assertEquals(0, $penalty);
    }

    public function test_denda_setelah_melewati_deadline()
    {
        // Explicitly set return date to today
        $today = now()->format('Y-m-d');
        $this->transaction->update(['tgl_kembali' => $today]);
        
        // Set time to 22:01 on return day
        $this->travelTo(now()->setTime(22, 1));

        $this->transaction->refresh();
        $penalty = $this->transaction->calculateLatePenalty();
        
        $this->assertEquals(100000, $penalty);
    }

    public function test_denda_terlambat_harian()
    {
        // Set time to 2 days after return date
        $this->travelTo(now()->addDays(2)->setTime(22, 1));

        $this->transaction->refresh();
        $penalty = $this->transaction->calculateLatePenalty();
        
        $this->assertEquals(200000, $penalty); // Should be equal to two days' rental fee
    }

    public function test_tidak_didenda_ketika_transaksi_selesai_dan_melewati_deadline()
    {
        // Set time to after deadline
        $this->travelTo(now()->setTime(22, 1));
        
        // Change status
        $this->transaction->update(['status' => 'selesai']);
        $this->transaction->refresh();
        
        $penalty = $this->transaction->calculateLatePenalty();
        
        $this->assertEquals(0, $penalty);
    }

    public function test_api_mengembalikan_nilai_denda_terbaru()
    {
        // Set time to after deadline
        $this->travelTo(now()->setTime(22, 1));

        $response = $this->actingAs($this->user)
            ->getJson('/api/user-transactions'); // Adjust this route to match your actual API route

        $response->assertStatus(200)
            ->assertJsonPath('data.0.total_denda', 100000);
    }


    public function test_tidak_batal_sebelum_diambil_dan_belum_deadline()
    {
        // Create a transaction with status 'pembayaran terkonfirmasi'
        $transaction = TransaksiSewa::create([
            'user_id' => $this->user->id,
            'total_harga' => 100000,
            'tgl_pinjam' => now()->format('Y-m-d'),
            'tgl_kembali' => now()->addDay()->format('Y-m-d'), // Tomorrow
            'status' => 'pembayaran terkonfirmasi',
            'metode_bayar' => 'transferbank'
        ]);

        // Set time to 22:01 on return day
        $this->travelTo(now()->setTime(22, 01));

        // Call getUserTransactions
        $response = $this->actingAs($this->user)->getJson('/api/transactions');

        // Refresh transaction from database
        $transaction->refresh();

        // Assert the transaction status remains 'pembayaran terkonfirmasi'
        $this->assertEquals('pembayaran terkonfirmasi', $transaction->status);
    }


    public function test_batal_setelah_deadline_pengambilan()
    {
        // Create a transaction with status 'pembayaran terkonfirmasi'
        $transaction = TransaksiSewa::create([
            'user_id' => $this->user->id,
            'total_harga' => 100000,
            'tgl_pinjam' => now()->format('Y-m-d'),
            'tgl_kembali' => now()->format('Y-m-d'), // Today
            'status' => 'pembayaran terkonfirmasi',
            'metode_bayar' => 'transferbank'
        ]);

        // Set time to 22:01 on pickup deadline
        $this->travelTo(now()->setTime(22, 1));

        // Call getUserTransactions which includes the auto-cancellation check
        $response = $this->actingAs($this->user)->getJson('/api/user-transactions');

        // Refresh transaction from database
        $transaction->refresh();

        // Assert the transaction was cancelled
        $this->assertEquals('dibatalkan', $transaction->status);
    }

    public function test_kembalikan_jumlah_stok_setelah_dibatalkan()
    {
        // Create a barang (item) first
        $barang = \App\Models\Barang::factory()->create([
            'stok' => 5,
        ]);

        // Create a transaction with status 'pembayaran terkonfirmasi'
        $transaction = TransaksiSewa::create([
            'user_id' => $this->user->id,
            'total_harga' => 100000,
            'tgl_pinjam' => now()->format('Y-m-d'),
            'tgl_kembali' => now()->format('Y-m-d'), // Today
            'status' => 'pembayaran terkonfirmasi',
            'metode_bayar' => 'transferbank'
        ]);

        // Create an ItemsOrder for the transaction
        \App\Models\ItemsOrder::create([
            'transaksi_sewa_id' => $transaction->id,
            'barang_id' => $barang->id,
            'quantity' => 2,
            'price_per_day' => 50000,
            'subtotal' => 100000
        ]);

        // Set time to 22:01 on pickup deadline
        $this->travelTo(now()->setTime(22, 1));

        // Call getUserTransactions which includes the auto-cancellation check
        $response = $this->actingAs($this->user)->getJson('/api/user-transactions');

        // Refresh models from database
        $transaction->refresh();
        $barang->refresh();

        // Assert the transaction was cancelled
        $this->assertEquals('dibatalkan', $transaction->status);
        
        // Assert the stock was returned (original 5 + returned 2 = 7)
        $this->assertEquals(7, $barang->stok);
    }
}