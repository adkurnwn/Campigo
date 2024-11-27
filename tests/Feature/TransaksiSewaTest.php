<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\TransaksiSewa;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TransaksiSewaTest extends TestCase
{
    //use RefreshDatabase;

    private $user;
    private $transaction;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test user
        $this->user = User::factory()->create();
        
        // Create a test transaction
        $this->transaction = TransaksiSewa::create([
            'user_id' => $this->user->id,
            'total_harga' => 100000, // Rp 100,000 per day
            'tgl_pinjam' => now(),
            'tgl_kembali' => now(),
            'status' => 'berlangsung',
            'metode_bayar' => 'cash',
        ]);
    }

    public function test_no_penalty_before_deadline()
    {
        // Set time to 21:59 on return day
        $this->travel(to: now()->setTime(21, 59));

        $this->transaction->refresh();
        $penalty = $this->transaction->calculateLatePenalty();
        
        $this->assertEquals(0, $penalty);
    }

    public function test_penalty_after_deadline()
    {
        // Set time to 22:01 on return day
        $this->travel(to: now()->setTime(22, 1));

        $this->transaction->refresh();
        $penalty = $this->transaction->calculateLatePenalty();
        
        $this->assertEquals(100000, $penalty); // Should be equal to one day's rental fee
    }

    public function test_multiple_days_penalty()
    {
        // Set time to 2 days after return date
        $this->travel(to: now()->addDays(2)->setTime(22, 1));

        $this->transaction->refresh();
        $penalty = $this->transaction->calculateLatePenalty();
        
        $this->assertEquals(200000, $penalty); // Should be equal to two days' rental fee
    }

    public function test_no_penalty_when_status_not_berlangsung()
    {
        // Set time to after deadline
        $this->travel(to: now()->setTime(22, 1));
        
        // Change status
        $this->transaction->update(['status' => 'selesai']);
        $this->transaction->refresh();
        
        $penalty = $this->transaction->calculateLatePenalty();
        
        $this->assertEquals(0, $penalty);
    }

    public function test_api_returns_updated_penalty()
    {
        // Set time to after deadline
        $this->travel(to: now()->setTime(22, 1));

        $response = $this->actingAs($this->user)
            ->getJson('/api/transactions'); // Adjust this route to match your actual API route

        $response->assertStatus(200)
            ->assertJsonPath('data.0.total_denda', 100000);
    }
}